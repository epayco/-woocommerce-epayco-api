<?php

namespace Epayco\Woocommerce\Hooks;

use Exception;
use Epayco\Woocommerce\Configs\Seller;
use Epayco\Woocommerce\Order\OrderMetadata;
use Epayco\Woocommerce\Configs\Store;
use Epayco\Woocommerce\Helpers\Cron;
use Epayco\Woocommerce\Helpers\CurrentUser;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Helpers\Nonce;
use Epayco\Woocommerce\Helpers\PaymentStatus;
use Epayco\Woocommerce\Helpers\Requester;
use Epayco\Woocommerce\Helpers\Url;
use Epayco\Woocommerce\Order\OrderStatus;
use Epayco\Woocommerce\Translations\AdminTranslations;
use Epayco\Woocommerce\Translations\StoreTranslations;
use Epayco\Woocommerce\Libraries\Logs\Logs;
use Epayco\Woocommerce\Libraries\Metrics\Datadog;
use Epayco\Woocommerce\Sdk\EpaycoSdk;

if (!defined('ABSPATH')) {
    exit;
}

class Order
{

    /**
     * @var Template
     */
    private $template;

    /**
     * @var OrderMetadata
     */
    private $orderMetadata;

    /**
     * @var OrderStatus
     */
    private $orderStatus;

    /**
     * @var StoreTranslations
     */
    private $storeTranslations;

    /**
     * @var AdminTranslations
     */
    private $adminTranslations;

    /**
     * @var Store
     */
    private $store;

    /**
     * @var Seller
     */
    private $seller;

    /**
     * @var Scripts
     */
    private $scripts;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var Nonce
     */
    private $nonce;

    /**
     * @var Endpoints
     */
    private $endpoints;

    /**
     * @var Cron
     */
    private $cron;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @var Requester
     */
    private $requester;

    /**
     * @var Logs
     */
    private $logs;

    /**
     * @var Datadog
     */
    private $datadog;

    /**
     * @const
     */
    private const NONCE_ID = 'EP_ORDER_NONCE';

    /**
     * Order constructor
     * @param Template $template
     * @param OrderMetadata $orderMetadata
     * @param OrderStatus $orderStatus
     * @param AdminTranslations $adminTranslations
     * @param StoreTranslations $storeTranslations
     * @param Store $store
     * @param Seller $seller
     * @param Scripts $scripts
     * @param Url $url
     * @param Nonce $nonce
     * @param Endpoints $endpoints
     * @param Cron $cron
     * @param CurrentUser $currentUser
     * @param Requester $requester
     * @param Logs $logs
     */
    public function __construct(
        Template $template,
        OrderMetadata $orderMetadata,
        OrderStatus $orderStatus,
        AdminTranslations $adminTranslations,
        StoreTranslations $storeTranslations,
        Store $store,
        Seller $seller,
        Scripts $scripts,
        Url $url,
        Nonce $nonce,
        Endpoints $endpoints,
        Cron $cron,
        CurrentUser $currentUser,
        Requester $requester,
        Logs $logs
    ) {
        $this->template          = $template;
        $this->orderMetadata     = $orderMetadata;
        $this->orderStatus       = $orderStatus;
        $this->adminTranslations = $adminTranslations;
        $this->storeTranslations = $storeTranslations;
        $this->store             = $store;
        $this->seller            = $seller;
        $this->scripts           = $scripts;
        $this->url               = $url;
        $this->nonce             = $nonce;
        $this->endpoints         = $endpoints;
        $this->cron              = $cron;
        $this->currentUser       = $currentUser;
        $this->requester         = $requester;
        $this->logs              = $logs;
        $this->datadog           = Datadog::getInstance();

        $this->sdk  = $this->getSdkInstance();

        $this->registerStatusSyncMetaBox();
        $this->registerSyncPendingStatusOrdersAction();
        $this->endpoints->registerAjaxEndpoint('ep_sync_payment_status', [$this, 'paymentStatusSync']);
    }


    /**
     * Get SDK instance
     */
    public function getSdkInstance():EpaycoSdk
    {

        $public_key = $this->seller->getCredentialsPublicKeyPayment();
        $private_key = $this->seller->getCredentialsPrivateKeyPayment();
        $pCustId = $this->seller->getCredentialsPCustId();
        $pKey = $this->seller->getCredentialsPkey();
        $isTestMode = $this->seller->isTestUser()?"true":"false";
        $idioma = substr(get_locale(), 0, 2);
        return new EpaycoSdk([
            "apiKey" => $public_key,
            "privateKey" =>$private_key,
            "lenguage" => strtoupper($idioma),
            "test" => $isTestMode
        ],
            "",
            $public_key,
            $private_key,
            $pCustId,
            $pKey
        );
    }

    /**
     * Registers the Status Sync Metabox
     */
    private function registerStatusSyncMetabox(): void
    {
        $this->registerMetaBox(function ($postOrOrderObject) {
            $order = ($postOrOrderObject instanceof \WP_Post) ? wc_get_order($postOrOrderObject->ID) : $postOrOrderObject;

            if (!$order || !$this->getLastPaymentInfo($order)) {
                return;
            }

            $paymentMethod     = $this->orderMetadata->getUsedGatewayData($order);
            $isMpPaymentMethod = array_filter($this->store->getAvailablePaymentGateways(), function ($gateway) use ($paymentMethod) {
                return $gateway::ID === $paymentMethod || $gateway::WEBHOOK_API_NAME === $paymentMethod;
            });

            if (!$isMpPaymentMethod) {
                return;
            }

            $this->loadScripts($order);

            $this->addMetaBox(
                'ep_payment_status_sync',
                $this->adminTranslations->statusSync['metabox_title'],
                'admin/order/payment-status-metabox-content.php',
                $this->getMetaboxData($order)
            );
        });
    }

    /**
     * Load the Status Sync Metabox script and style
     *
     * @param \WC_Order $order
     */
    private function loadScripts(\WC_Order $order): void
    {
        $this->scripts->registerStoreScript(
            'ep_payment_status_sync',
            $this->url->getPluginFileUrl('assets/js/admin/order/payment-status-sync', '.js'),
            [
                'order_id' => $order->get_id(),
                'nonce' => $this->nonce->generateNonce(self::NONCE_ID),
            ]
        );

        $this->scripts->registerStoreStyle(
            'ep_payment_status_sync',
            $this->url->getPluginFileUrl('assets/css/admin/order/payment-status-sync', '.css')
        );
    }

    /**
     * Get the data to be renreded on the Status Sync Metabox
     *
     * @param \WC_Order $order
     *
     * @return array
     */
    private function getMetaboxData(\WC_Order $order): array
    {
        $paymentInfo  = $this->getLastPaymentInfo($order);
        $paymentInfo = json_decode(json_encode($paymentInfo), true);
        //$paymentInfo = json_decode('{"success":true,"titleResponse":"Successful consult","textResponse":"successful consult","lastAction":"successful consult","data":{"pagination":{"totalCount":1,"limit":50,"page":1},"data":[{"referencePayco":101638598,"referenceClient":"32_test_1","transactionDate":"2024-10-19","description":"my coffe subscription","paymentMethod":"DP","amount":22000,"status":"Rechazada","test":true,"currency":"COP","transactionDateTime":"2024-10-19 16:45:18","iva":0,"bank":"DaviPlata","card":"DP","receipt":"10163859820241019112993","authorization":"000000","response":"C\u00f3digo de confirmaci\u00f3n incorrecto","trmdia":null,"docType":"CC","document":"8019","names":"Paola","lastnames":"Margarita","cicloPse":null}],"aggregations":{"status":[{"key":"Rechazada","doc_count":1}],"transactionType":{"produccion":{"doc_count":0},"pruebas":{"doc_count":1}},"transactionFranchises":{"American Express":{"doc_count":0},"Baloto":{"doc_count":0},"Bot\u00f3n Bancolombia":{"doc_count":0},"Codensa":{"doc_count":0},"Credibanco Bot\u00f3n":{"doc_count":0},"Cr\u00e9dito Credencial":{"doc_count":0},"Cr\u00e9dito Mastercard":{"doc_count":0},"Cr\u00e9dito Visa":{"doc_count":0},"C\u00f3digo QR":{"doc_count":0},"Daviplata":{"doc_count":1},"Daviplata App":{"doc_count":0},"Debito Mastercard":{"doc_count":0},"Debito Visa":{"doc_count":0},"Diners Club":{"doc_count":0},"D\u00e9bito Autom\u00e1tico Interbancario":{"doc_count":0},"Efecty":{"doc_count":0},"Epm":{"doc_count":0},"Gana":{"doc_count":0},"PSE":{"doc_count":0},"PayPal":{"doc_count":0},"Punto Red":{"doc_count":0},"Puntos Colombia":{"doc_count":0},"Puntos y Cr\u00e9dito Davivienda":{"doc_count":0},"Recarga Daviplata PSE":{"doc_count":0},"Red Servi":{"doc_count":0},"SafetyPay":{"doc_count":0},"Sin medio de Pago":{"doc_count":0},"Split Payment":{"doc_count":0},"Split Receiver Fee":{"doc_count":0},"Sured":{"doc_count":0},"Tarjeta Mef\u00eda":{"doc_count":0}},"transactionStatus":{"Abandonada":{"doc_count":0},"Aceptada":{"doc_count":0},"Antifraude":{"doc_count":0},"Cancelada":{"doc_count":0},"Expirada":{"doc_count":0},"Fallida":{"doc_count":0},"Iniciada":{"doc_count":0},"Pendiente":{"doc_count":0},"Rechazada":{"doc_count":1},"Retenida":{"doc_count":0},"Reversada":{"doc_count":0}}}}}', true);
        $status = 'pending';
        $alert_title = '';
        foreach ($paymentInfo['data']['data'] as $data) {
            $status = $data['status'];
            $alert_title = $data['response'];
            $ref_payco = $data['referencePayco'];
            $test = $data['test'] ? 'Pruebas' : 'Producción';
            $transactionDateTime= $data['transactionDateTime'];
            $bank= $data['bank'];
            $authorization= $data['authorization'];
        }

        $paymentStatusType = PaymentStatus::getStatusType(strtolower($status));

        $cardContent = PaymentStatus::getCardDescription(
            $this->adminTranslations->statusSync,
            'by_collector',
            false
        );

        switch ($paymentStatusType) {
            case 'success':
                return [
                    'card_title'        => $this->adminTranslations->statusSync['card_title'],
                    'img_src'           => $this->url->getPluginFileUrl('assets/images/icons/icon-success', '.png', true),
                    'alert_title'       => $alert_title,
                    'alert_description' => $alert_title,
                    'link'              => 'https://www.epayco.com',
                    'border_left_color' => '#00A650',
                    'link_description'  => $this->adminTranslations->statusSync['link_description_success'],
                    'sync_button_text'  => $this->adminTranslations->statusSync['sync_button_success'],
                    'ref_payco'         => $ref_payco,
                    'test'              => $test,
                    'transactionDateTime'              => $transactionDateTime,
                    'bank'              => $bank,
                    'authorization'     => $authorization
                ];

            case 'pending':
                return [
                    'card_title'        => $this->adminTranslations->statusSync['card_title'],
                    'img_src'           => $this->url->getPluginFileUrl('assets/images/icons/icon-alert', '.png', true),
                    'alert_title'       => $alert_title,
                    'alert_description' => $alert_title,
                    'link'              => 'https://www.epayco.com',
                    'border_left_color' => '#f73',
                    'link_description'  => $this->adminTranslations->statusSync['link_description_pending'],
                    'sync_button_text'  => $this->adminTranslations->statusSync['sync_button_pending'],
                    'ref_payco'         => $ref_payco,
                    'test'              => $test,
                    'transactionDateTime'              => $transactionDateTime,
                    'bank'              => $bank,
                    'authorization'     => $authorization
                ];

            case 'rejected':
            case 'refunded':
            case 'charged_back':
                return [
                    'card_title'        => $this->adminTranslations->statusSync['card_title'],
                    'img_src'           => $this->url->getPluginFileUrl('assets/images/icons/icon-warning', '.png', true),
                    'alert_title'       => $alert_title,
                    'alert_description' => $alert_title,
                    'link'              => $this->adminTranslations->links['reasons_refusals'],
                    'border_left_color' => '#F23D4F',
                    'link_description'  => $this->adminTranslations->statusSync['link_description_failure'],
                    'sync_button_text'  => $this->adminTranslations->statusSync['sync_button_failure'],
                    'ref_payco'         => $ref_payco,
                    'test'              => $test,
                    'transactionDateTime'              => $transactionDateTime,
                    'bank'              => $bank,
                    'authorization'     => $authorization
                ];

            default:
                return [];
        }
    }

    /**
     * Get the last order payment info
     *
     * @param \WC_Order $order
     *
     * @return bool|object
     */
    private function getLastPaymentInfo(\WC_Order $order)
    {
        try {
            $paymentsIds   = explode(',', $this->orderMetadata->getPaymentsIdMeta($order));
            $lastPaymentId = trim(end($paymentsIds));

            if (!$lastPaymentId) {
                return false;
            }
            $data = array(
                "filter" => array("referencePayco" => $lastPaymentId),
                "success" =>true
            );
            return $this->sdk->transaction->get($data);

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Updates the order based on current payment status from API
     *
     */
    public function paymentStatusSync(): void
    {
        try {
            $this->nonce->validateNonce(self::NONCE_ID, Form::sanitizedPostData('nonce'));
            $this->currentUser->validateUserNeededPermissions();

            $orderId = Form::sanitizedPostData('order_id');
            $order = wc_get_order($orderId);
            $this->syncOrderStatus($order);

            wp_send_json_success(
                $this->adminTranslations->statusSync['response_success']
            );
        } catch (\Exception $e) {
            $this->logs->file->error(
                "ePayco gave error in payment status Sync: {$e->getMessage()}",
                __CLASS__
            );

            wp_send_json_error(
                $this->adminTranslations->statusSync['response_error'] . ' ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Syncs the order in woocommerce to epayco
     *
     * @param \WC_Order $order
     *
     * @return void
     */
    public function syncOrderStatus(\WC_Order $order): void
    {
        $paymentData = $this->getLastPaymentInfo($order);
        if (!$paymentData) {
            throw new Exception('Couldn\'t find payment');
        }

        $this->orderStatus->processStatus($paymentData['status'], (array) $paymentData, $order, $this->orderMetadata->getUsedGatewayData($order));
    }

    /**
     * Register action that sync orders with pending status with corresponding status in epayco
     *
     * @return void
     */
    public function registerSyncPendingStatusOrdersAction(): void
    {
        add_action('epayco_sync_pending_status_order_action', function () {
            try {
                $orders = wc_get_orders(array(
                    'limit'    => -1,
                    'status'   => 'pending',
                    'meta_query' => array(
                        array(
                            'key' => 'is_production_mode',
                            'compare' => 'EXISTS'
                        ),
                        array(
                            'key' => 'blocks_payment',
                            'compare' => 'EXISTS'
                        )
                    )
                ));

                foreach ($orders as $order) {
                    $this->syncOrderStatus($order);
                }

                $this->sendEventOnAction('success');
            } catch (\Exception $ex) {
                $error_message = "Unable to update batch of orders on action got error: {$ex->getMessage()}";

                $this->logs->file->error(
                    $error_message,
                    __CLASS__
                );
                $this->sendEventOnAction('error', $error_message);
            }
        });
    }

    /**
     * Register/Unregister cron job that sync pending orders
     *
     * @return void
     */
    public function toggleSyncPendingStatusOrdersCron(string $enabled): void
    {
        $action = 'epayco_sync_pending_status_order_action';

        if ($enabled == 'yes') {
            $this->cron->registerScheduledEvent('hourly', $action);
        } else {
            $this->cron->unregisterScheduledEvent($action);
        }

        $this->sendEventOnToggle($enabled);
    }

    /**
     * Register meta box addition on order page
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function registerMetaBox($callback): void
    {
        add_action('add_meta_boxes_shop_order', $callback);
        add_action('add_meta_boxes_woocommerce_page_wc-orders', $callback);
    }

    /**
     * Add a meta box to screen
     *
     * @param string $id
     * @param string $title
     * @param string $name
     * @param array $args
     *
     * @return void
     */
    public function addMetaBox(string $id, string $title, string $name, array $args): void
    {
        add_meta_box($id, $title, function () use ($name, $args) {
            $this->template->getWoocommerceTemplate($name, $args);
        });
    }

    /**
     * Register order details after order table
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function registerOrderDetailsAfterOrderTable($callback): void
    {
        add_action('woocommerce_order_details_after_order_table', $callback);
    }

    /**
     * Register email before order table
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function registerEmailBeforeOrderTable($callback): void
    {
        add_action('woocommerce_email_before_order_table', $callback);
    }

    /**
     * Register total line after WooCommerce order totals callback
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function registerAdminOrderTotalsAfterTotal($callback): void
    {
        add_action('woocommerce_admin_order_totals_after_total', $callback);
    }

    /**
     * Add order note
     *
     * @param \WC_Order $order
     * @param string $description
     * @param int $isCustomerNote
     * @param bool $addedByUser
     *
     * @return void
     */
    public function addOrderNote(\WC_Order $order, string $description, int $isCustomerNote = 0, bool $addedByUser = false)
    {
        $order->add_order_note($description, $isCustomerNote, $addedByUser);
    }

    /**
     * Set ticket metadata in the order
     *
     * @param \WC_Order $order
     * @param $data
     *
     * @return void
     */
    public function setTicketMetadata(\WC_Order $order, $data): void
    {
        $externalResourceUrl = $data['urlPayment'];
        $this->orderMetadata->setTicketTransactionDetailsData($order, $externalResourceUrl);
        $order->save();
    }

    /**
     * Set ticket metadata in the order
     *
     * @param \WC_Order $order
     * @param $data
     *
     * @return void
     */
    public function setDaviplataMetadata(\WC_Order $order, $data): void
    {
        $externalResourceUrl = $data['urlPayment'];
        $this->orderMetadata->setDaviplataTransactionDetailsData($order, $externalResourceUrl);
        $order->save();
    }


    /**
     * Send an datadog event inside the sync order status action on fail and success
     */
    private function sendEventOnAction($value, $message = null)
    {
        $this->datadog->sendEvent('order_sync_status_action', $value, $message);
    }

    /**
     * Send an datadog event when an seller toggles (activating or deactivating) the cron button
     */
    private function sendEventOnToggle($value)
    {
        $this->datadog->sendEvent('order_toggle_cron', $value);
    }
}
