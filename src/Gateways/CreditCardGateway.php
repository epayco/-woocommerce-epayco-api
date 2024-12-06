<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Exceptions\InvalidCheckoutDataException;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Helpers\Numbers;
use Epayco\Woocommerce\Helpers\PaymentStatus;
use Epayco\Woocommerce\Transactions\CreditCardTransaction;
use Epayco\Woocommerce\Exceptions\ResponseStatusException;

if (!defined('ABSPATH')) {
    exit;
}

class CreditCardGateway extends AbstractGateway
{
    /**
     * @const
     */
    public const ID = 'woo-epayco-creditcard';

    /**
     * @const
     */
    public const CHECKOUT_NAME = 'checkout-creditcard';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = 'WC_Epayco_Creditcard_Gateway';

    /**
     * @const
     */
    public const LOG_SOURCE = 'Epayco_CreditcardGateway';

    /**
     * CreditCardGateway constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->creditcardGatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->creditcardCheckout;

        $this->id        = self::ID;
        $this->icon      = $this->epayco->hooks->gateway->getGatewayIcon('icon-blue-card');
        $this->iconAdmin = $this->epayco->hooks->gateway->getGatewayIcon('icon-blue-card-admin');
        $this->title     = $this->epayco->storeConfig->getGatewayTitle($this, $this->adminTranslations['gateway_title']);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['gateway_method_title'];
        $this->method_description = $this->adminTranslations['gateway_method_description'];

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderThankYouPage']);
        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_API_NAME, [$this, 'webhook']);

        $this->epayco->helpers->currency->handleCurrencyNotices($this);
    }

    /**
     * Get checkout name
     *
     * @return string
     */
    public function getCheckoutName(): string
    {
        return self::CHECKOUT_NAME;
    }

    /**
     * Init form fields for checkout configuration
     *
     * @return void
     */
    public function init_form_fields(): void
    {
        if ($this->addMissingCredentialsNoticeAsFormField()) {
            return;
        }

        parent::init_form_fields();

        $this->form_fields = array_merge($this->form_fields, [
            'header' => [
                'type'        => 'ep_config_title',
                'title'       => $this->adminTranslations['header_title'],
                'description' => $this->adminTranslations['header_description'],
            ],
            'enabled' => [
                'type'         => 'ep_toggle_switch',
                'title'        => $this->adminTranslations['enabled_title'],
                'subtitle'     => $this->adminTranslations['enabled_subtitle'],
                'default'      => 'no',
                'descriptions' => [
                    'enabled'  => $this->adminTranslations['enabled_descriptions_enabled'],
                    'disabled' => $this->adminTranslations['enabled_descriptions_disabled'],
                ],
            ],
            'title' => [
                'type'        => 'text',
                'title'       => $this->adminTranslations['title_title'],
                'description' => $this->adminTranslations['title_description'],
                'default'     => $this->adminTranslations['title_default'],
                'desc_tip'    => $this->adminTranslations['title_desc_tip'],
                'class'       => 'limit-title-max-length',
            ],
            'card_info_helper' => [
                'type'  => 'title',
                'value' => '',
            ]
        ]);
    }

    /**
     * Added gateway scripts
     *
     * @param string $gatewaySection
     *
     * @return void
     */
    public function payment_scripts(string $gatewaySection): void
    {
        parent::payment_scripts($gatewaySection);

        if ($this->canCheckoutLoadScriptsAndStyles()) {
            $this->registerCheckoutScripts();
        }
    }

    /**
     * Register checkout scripts
     *
     * @return void
     */
    public function registerCheckoutScripts(): void
    {
        parent::registerCheckoutScripts();

        /*$this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_sdk',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/credits/library', '.js')
        );*/

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_creditcard_checkout',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/creditcard/ep-creditcard-checkout', '.js'),
            [
                'public_key_epayco'        => $this->epayco->sellerConfig->getCredentialsPublicKeyPayment()
            ]
        );
    }

    /**
     * Render gateway checkout template
     *
     * @return void
     */
    public function payment_fields(): void
    {
        $this->epayco->hooks->template->getWoocommerceTemplate(
            'public/checkouts/creditcard-checkout.php',
            $this->getPaymentFieldsParams()
        );
    }

    /**
     * Get Payment Fields params
     *
     * @return array
     */
    public function getPaymentFieldsParams(): array
    {
        return [
            'test_mode'                        => $this->epayco->storeConfig->isTestMode(),
            'test_mode_title'                  => $this->storeTranslations['test_mode_title'],
            'test_mode_description'            => $this->storeTranslations['test_mode_description'],
            'test_mode_link_text'              => $this->storeTranslations['test_mode_link_text'],
            'card_detail'                      => $this->storeTranslations['card_detail'],
            //'test_mode_link_src'               => $this->links['docs_integration_test'],
            'card_form_title'                  => $this->storeTranslations['card_form_title'],
            'card_holder_name_input_label'     => $this->storeTranslations['card_holder_name_input_label'],
            'card_holder_name_input_helper'    => $this->storeTranslations['card_holder_name_input_helper'],
            'card_number_input_label'          => $this->storeTranslations['card_number_input_label'],
            'card_number_input_helper'         => $this->storeTranslations['card_number_input_helper'],
            'card_expiration_input_label'      => $this->storeTranslations['card_expiration_input_label'],
            'card_expiration_input_helper'     => $this->storeTranslations['card_expiration_input_helper'],
            'card_expiration_input_invalid_length' => $this->storeTranslations['input_helper_message_expiration_date_invalid_value'],
            'customer_data'                       => $this->storeTranslations['customer_data'],
            'card_security_code_input_label'   => $this->storeTranslations['card_security_code_input_label'],
            'card_security_code_input_helper'  => $this->storeTranslations['card_security_code_input_helper'],
            'card_security_code_input_invalid_length' => $this->storeTranslations['input_helper_message_security_code_invalid_length'],
            'card_fees_input_label'   => $this->storeTranslations['card_fees_input_label'],
            'card_customer_title'              => $this->storeTranslations['card_customer_title'],
            'card_document_input_label'        => $this->storeTranslations['card_document_input_label'],
            'card_document_input_helper'       => $this->storeTranslations['card_document_input_helper'],
            'card_holder_address_input_label'   => $this->storeTranslations['card_holder_address_input_label'],
            'card_holder_address_input_helper'  => $this->storeTranslations['card_holder_address_input_helper'],
            'card_holder_email_input_label'    => $this->storeTranslations['card_holder_email_input_label'],
            'card_holder_email_input_helper'   => $this->storeTranslations['card_holder_email_input_helper'],
            'card_holder_email_input_invalid'   => $this->storeTranslations['input_helper_message_card_holder_email'],
            'input_ind_phone_label'            => $this->storeTranslations['input_ind_phone_label'],
            'input_ind_phone_helper'           => $this->storeTranslations['input_ind_phone_helper'],
            'input_country_label'              => $this->storeTranslations['input_country_label'],
            'input_country_helper'             => $this->storeTranslations['input_country_helper'],
            'terms_and_conditions_label'       => $this->storeTranslations['terms_and_conditions_label'],
            'terms_and_conditions_description' => $this->storeTranslations['terms_and_conditions_description'],
            'terms_and_conditions_link_text'   => $this->storeTranslations['terms_and_conditions_link_text'],
            'terms_and_conditions_link_src'    => $this->links['epayco_terms_and_conditions'],
            'site_id'                          => $this->epayco->sellerConfig->getSiteId() ?: $this->epayco->helpers->country::SITE_ID_MLA,
        ];
    }

    /**
     * Process payment and create woocommerce order
     *
     * @param $order_id
     *
     * @return array
     */
    public function process_payment($order_id): array
    {
        $order = wc_get_order($order_id);
        try {
            $checkout = $this->getCheckoutEpaycoCredits($order);

            parent::process_payment($order_id);

            $checkout['token'] = $checkout['cardTokenId'] ?? $checkout['cardtokenid'];
            if (
                !empty($checkout['token'])
            ) {
                $this->transaction = new CreditCardTransaction($this, $order, $checkout);
                $redirect_url =get_site_url() . "/";
                $redirect_url = add_query_arg( 'wc-api', self::WEBHOOK_API_NAME, $redirect_url );
                $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
                $confirm_url = $redirect_url.'&confirmation=1';
                $checkout['confirm_url'] = $confirm_url;
                $checkout['response_url'] = $order->get_checkout_order_received_url();
                $response = $this->transaction->createTcPayment($order_id, $checkout);
                $response = json_decode(json_encode($response), true);
                if (is_array($response) && $response['success']) {
                    $ref_payco = $response['data']['refPayco']??$response['data']['ref_payco'];
                    $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, [$ref_payco]);
                    if (in_array(strtolower($response['data']['estado']),["pendiente","pending"])) {
                        $order->update_status("on-hold");
                        $this->epayco->woocommerce->cart->empty_cart();
                        //$this->epayco->hooks->order->addOrderNote($order, $this->storeTranslations['customer_not_paid']);
                        $urlReceived = $order->get_checkout_order_received_url();
                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];
                    }
                    if (in_array(strtolower($response['data']['estado']),["aceptada","acepted"])) {
                        $order->update_status("processing");
                        $this->epayco->woocommerce->cart->empty_cart();
                        $urlReceived = $order->get_checkout_order_received_url();
                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];
                    }if (in_array(strtolower($response['data']['estado']),["rechazada","fallida","cancelada","abandonada"])) {
                        $urlReceived = wc_get_checkout_url();
                        $return = [
                            'result'   => 'fail',
                            'message' => $response['data']['respuesta'],
                            'redirect' => $urlReceived,
                        ];
                    }
                    return $return;
                }else{
                    $messageError = $response['message'];
                    $errorMessage = "";
                    if (isset($response['data']['errors'])) {
                        $errors = $response['data']['errors'];
                        foreach ($errors as $error) {
                            $errorMessage = $error['errorMessage'] . "\n";
                        }
                    } elseif (isset($response['data']['error']['errores'])) {
                        $errores = $response['data']['error']['errores'];
                        foreach ($errores as $error) {
                            $errorMessage = $error['errorMessage'] . "\n";
                        }
                    }
                    $processReturnFailMessage = $messageError. " " . $errorMessage;
                    return $this->returnFail($processReturnFailMessage, $order);
                }
            }else{
                $processReturnFailMessage = "Token incorrect ";
                return $this->returnFail($processReturnFailMessage, $order);
            }

        } catch (\Exception $e) {
            return $this->processReturnFail(
                $e,
                $e->getMessage(),
                self::LOG_SOURCE,
                (array) $order,
                true
            );
        }
    }

    /**
     * Get checkout epayco credits
     *
     * @param $order
     *
     * @return array
     */
    private function getCheckoutEpaycoCredits($order): array
    {
        $checkout = [];

        if (isset($_POST['epayco_creditcard'])) {
            $checkout = Form::sanitizedPostData('epayco_creditcard');
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "no");
        } else {
            $checkout = $this->processBlocksCheckoutData('epayco_creditcard', Form::sanitizedPostData());
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "yes");
        }

        return $checkout;
    }
    
    /**
     * Render thank you page
     *
     * @param $order_id
     */
    public function renderThankYouPage($order_id): void
    {
        $order        = wc_get_order($order_id);
        $lastPaymentId  =  $this->epayco->orderMetadata->getPaymentsIdMeta($order);
        $paymentInfo = json_decode(json_encode($lastPaymentId), true);

        if (empty($paymentInfo)) {
            return;
        }
        $data = array(
            "filter" => array("referencePayco" => $paymentInfo),
            "success" =>true
        );
        $transactionDetails = $this->sdk->transaction->get($data);
        $transactionInfo = json_decode(json_encode($transactionDetails), true);

        if (empty($transactionInfo)) {
            return;
        }
 
        $status = 'pending';
        $alert_title = '';
        foreach ($transactionInfo['data']['data'] as $data) {
            $status = $data['status'];
            $alert_title = $data['response'];
            $ref_payco = $data['referencePayco'];
            $test = $data['test'] ? 'Pruebas' : 'Producción';
            $transactionDateTime= $data['transactionDateTime'];
            $bank= $data['bank'];
            $authorization= $data['authorization'];
            $factura = $data['referenceClient'];
            $descripcion = $data['description'];
            $valor = $data['amount'];
            $iva = $data['iva'];
            $estado = $data['status'];
            $currency = $data['currency'];
            $name =  $data['names']." ". $data['lastnames'];
            $card = $data['card'];
            switch ($status) {
                case 'Aceptada': {
                    $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('check');
                    $iconColor = '#67C940';
                    $message = $this->storeTranslations['success_message'];
                }break;
                case 'Pendiente':
                case 'Pending':{
                    $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('warning');
                    $iconColor = '#FFD100';
                    $message = $this->storeTranslations['pending_message'];
                }break;
                default: {
                    $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('error');
                    $iconColor = '#E1251B';
                    $message = $this->storeTranslations['fail_message'];
                }break;
            }
        }
        $paymentStatusType = PaymentStatus::getStatusType(strtolower($status));
        $this->transaction = new CreditCardTransaction($this, $order, []);
        $transaction = [
            'status' => $status,
            'type' => "",
            'refPayco' => $ref_payco,
            'factura' => $factura,
            'descripcion_order' => $descripcion,
            'valor' => $valor,
            'iva' => $iva,
            'estado' => $estado,
            'respuesta' => $alert_title,
            'fecha' => $transactionDateTime,
            'currency' => $currency,
            'name' => $name,
            'card' => $card,
            'message' => $message,
            'error_message' => $this->storeTranslations['error_message'],
            'error_description' => $this->storeTranslations['error_description'],
            'payment_method'  => $this->storeTranslations['payment_method'],
            'response'=> $this->storeTranslations['response'],
            'dateandtime' => $this->storeTranslations['dateandtime'],
            'authorization' => $authorization,
            'iconUrl' => $iconUrl,
            'iconColor' => $iconColor,
            'ip' => $this->transaction->getCustomerIp(),
            'totalValue' => $this->storeTranslations['totalValue'],
            'description' => $this->storeTranslations['description'],
            'reference' => $this->storeTranslations['reference'],
            'purchase' => $this->storeTranslations['purchase'],
            'iPaddress' => $this->storeTranslations['iPaddress'],
            'receipt' => $this->storeTranslations['receipt'],
            'authorizations' => $this->storeTranslations['authorization'],
            'paymentMethod'  => $this->storeTranslations['paymentMethod'],
            'epayco_refecence'  => $this->storeTranslations['epayco_refecence'],
        ];

        if (empty($transaction)) {
            return;
        }

        $this->epayco->hooks->template->getWoocommerceTemplate(
            'public/order/order-received.php',
            $transaction
        );
    }

}
