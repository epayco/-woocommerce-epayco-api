<?php
namespace Epayco\Woocommerce\Hooks;

use Exception;
use Epayco\Woocommerce\Helpers\PaymentStatus;
use Epayco\Woocommerce\Helpers\CurrentUser;
use Epayco\Woocommerce\Order\OrderMetadata;
use Epayco\Woocommerce\Hooks\Template;
use Epayco\Woocommerce\Configs\Seller;
use Epayco\Woocommerce\Configs\Store;
use Epayco\Woocommerce\Helpers\Url;
use Epayco\Woocommerce\Translations\AdminTranslations;
use Epayco\Woocommerce\Translations\StoreTranslations;
use WC_Order;
use WP_Post;
use Epayco as EpaycoSdk;

if (!defined('ABSPATH')) {
    exit;
}

class Order
{

    private const NONCE_ID = 'EP_ORDER_NONCE';
    /**
     * Order constructor
     * @param Template $template
     * @param OrderMetadata $orderMetadata
     * @param AdminTranslations $adminTranslations
     * @param StoreTranslations $storeTranslations
     * @param Store $store
     * @param Seller $seller
     * @param Scripts $scripts
     * @param Url $url
     * @param Endpoints $endpoints
     * @param CurrentUser $currentUser
     */
     public function __construct(
        Template $template,
        OrderMetadata $orderMetadata,
        AdminTranslations $adminTranslations,
        StoreTranslations $storeTranslations,
        Store $store,
        Seller $seller,
        Scripts $scripts,
        Url $url,
        Endpoints $endpoints,
        CurrentUser $currentUser
     ){
         $this->template          = $template;
         $this->orderMetadata     = $orderMetadata;
         $this->adminTranslations = $adminTranslations;
         $this->storeTranslations = $storeTranslations;
         $this->store             = $store;
         $this->seller            = $seller;
         $this->scripts           = $scripts;
         $this->url               = $url;
         $this->endpoints         = $endpoints;
         $this->currentUser       = $currentUser;

         $this->sdk         = $this->getSdkInstance();

         $this->registerStatusSyncMetaBox();
     }

    /**
     * Get SDK instance
     */
    public function getSdkInstance()
    {

        $lang = get_locale();
        $lang = explode('_', $lang);
        $lang = $lang[0];
        $public_key = $this->seller->getCredentialsPublicKeyPayment();
        $private_key = $this->seller->getCredentialsPrivateKeyPayment();
        //$isTestMode = $this->seller->isTestUser()?"true":"false";
        $isTestMode = $this->seller->isTestMode()?"true":"false";
        return new EpaycoSdk\Epayco(
            [
                "apiKey" => $public_key,
                "privateKey" => $private_key,
                "lenguage" => strtoupper($lang),
                "test" => $isTestMode
            ]
        );
    }

    /**
     * Set ticket metadata in the order
     *
     * @param WC_Order $order
     * @param $data
     *
     * @return void
     */
    public function setTicketMetadata(WC_Order $order, $data): void
    {
        $externalResourceUrl = $data['urlPayment'];
        $this->orderMetadata->setTicketTransactionDetailsData($order, $externalResourceUrl);
        $order->save();
    }

    /**
     * Set ticket metadata in the order
     *
     * @param WC_Order $order
     * @param $data
     *
     * @return void
     */
    public function setDaviplataMetadata(WC_Order $order, $data): void
    {
        $externalResourceUrl = $data['urlPayment'];
        $this->orderMetadata->setDaviplataTransactionDetailsData($order, $externalResourceUrl);
        $order->save();
    }

    /**
     * Registers the Status Sync Metabox
     */
    private function registerStatusSyncMetabox(): void
    {
        $this->registerMetaBox(function ($postOrOrderObject) {
            $order = ($postOrOrderObject instanceof WP_Post) ? wc_get_order($postOrOrderObject->ID) : $postOrOrderObject;

            if (!$order || !$this->getLastPaymentInfo($order)) {
                return;
            }

            $paymentMethod     = $this->orderMetadata->getUsedGatewayData($order);
            $isMpPaymentMethod = array_filter($this->store->getAvailablePaymentGateways(), function ($gateway) use ($paymentMethod) {
                return $gateway::ID === $paymentMethod || $gateway::WEBHOOK_API_NAME === $paymentMethod;
            });

            /*if (!$isMpPaymentMethod) {
                return;
            }*/

            $this->loadScripts($order);
            $epayco_order = $this->getMetaboxData($order);
            if(!$epayco_order){
                return;
            }

            $this->addMetaBox(
                'ep_payment_status_sync',
                $this->adminTranslations->statusSync['metabox_title'],
                'admin/order/payment-status-metabox-content.php',
                $this->getMetaboxData($order)
            );
        });
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
     * Load the Status Sync Metabox script and style
     *
     * @param WC_Order $order
     */
    private function loadScripts(WC_Order $order): void
    {
        $this->scripts->registerStoreScript(
            'mp_payment_status_sync',
            $this->url->getJsAsset('admin/order/payment-status-sync'),
            [
                'order_id' => $order->get_id(),
                'nonce' => self::generateNonce(self::NONCE_ID),
            ]
        );

        $this->scripts->registerStoreStyle(
            'mp_payment_status_sync',
            $this->url->getCssAsset('admin/order/payment-status-sync')
        );
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
     * Get the data to be renreded on the Status Sync Metabox
     *
     * @param WC_Order $order
     *
     * @return array|bool
     */
    private function getMetaboxData(WC_Order $order)
    {
        $paymentInfo  = $this->getLastPaymentInfo($order);
        if(!$paymentInfo->success){
            return false;
        }
        $status = 'pending';
        $alert_title = '';
        $order_id=false;
        foreach ($paymentInfo->data->data as $data) {
            $status = $data->status;
            $alert_title = $data->response;
            $ref_payco = $data->referencePayco;
            $test = $data->test ? 'Pruebas' : 'Producción';
            $transactionDateTime= $data->transactionDateTime;
            $bank= $data->bank;
            $authorization= $data->authorization;
            $order_id = $data->referenceClient;
        }
        if(!$order_id){
            return false;
        }
        $order = new WC_Order($order_id);
        $WooOrderstatus = $order->get_status();

        switch ($status) {
            case 'Aceptada':
                $orderstatus = 'approved';
                break;
            case 'Pendiente':
                $orderstatus = 'pending';
                break;
            default:
                $orderstatus = 'rejected';
                break;
        }
        $paymentStatusType = PaymentStatus::getStatusType(strtolower($orderstatus));
        $upload_order=false;
        if($WooOrderstatus == 'on-hold'||$WooOrderstatus == 'cancelled'){
            $upload_order=true;
        }

        $cardContent = PaymentStatus::getCardDescription(
            $this->adminTranslations->statusSync,
            'by_collector',
            false
        );

        switch ($paymentStatusType) {
            case 'success':{
                if($upload_order){
                    if($WooOrderstatus !== 'processing'){
                        $order->update_status("processing");
                    }
                }
                return [
                    'card_title'        => $this->adminTranslations->statusSync['card_title'],
                    'img_src'           => $this->url->getImageAsset('icons/icon-success'),
                    'alert_title'       => $alert_title,
                    'alert_description' => $alert_title,
                    'link'              => 'https://epayco.com',
                    'border_left_color' => '#00A650',
                    'link_description'  => $this->adminTranslations->statusSync['link_description_success'],
                    'sync_button_text'  => $this->adminTranslations->statusSync['sync_button_success'],
                    'ref_payco'         => $ref_payco,
                    'test'              => $test,
                    'transactionDateTime'              => $transactionDateTime,
                    'bank'              => $bank,
                    'authorization'     => $authorization
                ];
            }break;
            case 'pending':
                return [
                    'card_title'        => $this->adminTranslations->statusSync['card_title'],
                    'img_src'           => $this->url->getImageAsset('icons/icon-alert'),
                    'alert_title'       => $alert_title,
                    'alert_description' => $alert_title,
                    'link'              => 'https://epayco.com',
                    'border_left_color' => '#f73',
                    'link_description'  => $this->adminTranslations->statusSync['link_description_pending'],
                    'sync_button_text'  => $this->adminTranslations->statusSync['sync_button_pending'],
                    'ref_payco'         => $ref_payco,
                    'test'              => $test,
                    'transactionDateTime'              => $transactionDateTime,
                    'bank'              => $bank,
                    'authorization'     => $authorization
                ];
                break;
            case 'rejected':
            case 'refunded':
            case 'charged_back':{
                if($upload_order){
                    if($WooOrderstatus !== 'cancelled'){
                        $order->update_status("cancelled");
                    }
                }

                return [
                    'card_title'        => $this->adminTranslations->statusSync['card_title'],
                    'img_src'           => $this->url->getImageAsset('icons/icon-warning'),
                    'alert_title'       => $alert_title,
                    'alert_description' => $alert_title,
                    'link'              => 'reasons_refusals',
                    'border_left_color' => '#F23D4F',
                    'link_description'  => $this->adminTranslations->statusSync['link_description_failure'],
                    'sync_button_text'  => $this->adminTranslations->statusSync['sync_button_failure'],
                    'ref_payco'         => $ref_payco,
                    'test'              => $test,
                    'transactionDateTime'              => $transactionDateTime,
                    'bank'              => $bank,
                    'authorization'     => $authorization
                ];
            }break;
            default:
                return [];
        }
    }

    /**
     * Get the last order payment info
     *
     * @param WC_Order $order
     *
     * @return bool|AbstractCollection|AbstractEntity|object
     */
    private function getLastPaymentInfo(WC_Order $order)
    {
        try {
            $paymentsIds   = explode(',', $this->orderMetadata->getPaymentsIdMeta($order));
            $lastPaymentId = trim(end($paymentsIds));

            if (!$lastPaymentId) {
                return false;
            }
            $data = array(
                "filter" => array("referencePayco" => $paymentsIds[0]),
                "success" =>true
            );
            return $this->sdk->transaction->get($data);
            //return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Add order note
     *
     * @param WC_Order $order
     * @param string $description
     * @param int $isCustomerNote
     * @param bool $addedByUser
     *
     * @return void
     */
    public function addOrderNote(WC_Order $order, string $description, int $isCustomerNote = 0, bool $addedByUser = false)
    {
        $order->add_order_note($description, $isCustomerNote, $addedByUser);
    }

    /**
     * Generate wp_nonce
     *
     * @param string $id
     *
     * @return string
     */
    private static function generateNonce(string $id): string
    {
        $nonce = wp_create_nonce($id);

        if (!$nonce) {
            return '';
        }

        return $nonce;
    }
}