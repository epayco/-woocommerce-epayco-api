<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Helpers\PaymentStatus;
use Epayco\Woocommerce\Transactions\CreditCardTransaction;


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
    public const WEBHOOK_DONWLOAD = 'Donwload';

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
        // $this->icon      = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/tarjeta.png';
        $this->iconAdmin = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/tarjeta.png';
        $defaultTitle = (substr(get_locale(), 0, 2) === 'es') ? 'Tarjeta de crédito y débito' : 'Credit and Debit Cards';
        $this->title = $this->epayco->storeConfig->getGatewayTitle($this, $defaultTitle);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['gateway_method_title'];
        $this->method_description = $this->adminTranslations['gateway_method_description'];

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderThankYouPage']);
        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_DONWLOAD, [$this, 'validate_epayco_request']);
        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_API_NAME, [$this, 'webhook']);

        // Nuevo registro del script autofill
        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_creditcard_autofill',
            $this->epayco->helpers->url->getJsAsset('checkouts/epayco-autofill')
        );
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
        public function get_title() {
        $lang = substr(get_locale(), 0, 2);
        $description = ($lang === 'es')
        ? 'Visa, MasterCard, Amex, Diners y Codensa.'
        : 'Visa, MasterCard, Amex, Diners and Codensa.';
        return sprintf(
            '<div class="epayco-title-wrapper">
                <img class="epayco-brand-icons" src="https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/tarjetaCreditoDebito.png" alt="Medios de pago" />
                <span class="epayco-text">
                <span style="font-weight: bold;">%s</span>
                <span style="color: #888;">%s</span>                    
                </span>
            </div>',
            esc_html($this->title),
            esc_html($description)
        );
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
            'config_header' => [
                'type'        => 'mp_config_title',
                'title'       => $this->adminTranslations['header_title'],
                'description' => $this->adminTranslations['header_description'],
            ],
            'card_homolog_validate' => $this->getHomologValidateNoticeOrHidden(),
            'card_settings'  => [
                'type'  => 'mp_card_info',
                'value' => [
                    'title'       => $this->adminTranslations['card_settings_title'],
                    'subtitle'    => $this->adminTranslations['card_settings_subtitle'],
                    'button_text' => $this->adminTranslations['card_settings_button_text'],
                    'button_url'  => admin_url('admin.php?page=epayco-settings'),
                    //'icon'        => 'ep-icon-badge-info',
                    'icon'        =>  $this->epayco->hooks->gateway->getGatewayIcon('icon-info.png'),
                    'color_card'  => '',
                    'size_card'   => 'ep-card-body-size',
                    'target'      => '_self',
                ],
            ],
            'enabled' => [
                'type'         => 'mp_toggle_switch',
                'title'        => $this->adminTranslations['enabled_title'],
                'subtitle'     => $this->adminTranslations['enabled_subtitle'],
                'default'      => 'no',
                'descriptions' => [
                    'enabled'  => $this->adminTranslations['enabled_descriptions_enabled'],
                    'disabled' => $this->adminTranslations['enabled_descriptions_disabled'],
                ],
            ],
            // 'title' => [
            //     'type'        => 'text',
            //     'title'       => $this->adminTranslations['title_title'],
            //     'description' => $this->adminTranslations['title_description'],
            //     'default'     => $this->adminTranslations['title_default'],
            //     'desc_tip'    => $this->adminTranslations['title_desc_tip'],
            //     'class'       => 'limit-title-max-length',
            // ],
            // 'card_info_helper' => [
            //     'type'  => 'title',
            //     'value' => '',
            // ]
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
        $lang = get_locale();
        $lang = explode('_', $lang);
        $lang = $lang[0];
        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_creditcard_page',
            $this->epayco->helpers->url->getJsAsset('checkouts/creditcard/ep-creditcard-page')
        );

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_creditcard_elements',
            $this->epayco->helpers->url->getJsAsset('checkouts/creditcard/ep-creditcard-elements')
        );

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_creditcard_checkout',
            $this->epayco->helpers->url->getJsAsset('checkouts/creditcard/ep-creditcard-checkout'),
            [
                'site_id' => 'epayco',
                'public_key_epayco'        => $this->epayco->sellerConfig->getCredentialsPublicKeyPayment(),
                'lang' => $lang
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
            'public/checkout/creditcard-checkout.php',
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
        $idioma = substr(get_locale(), 0, 2);
        if ($idioma == 'es') {
            $termsAndCondiction = 'Términos y condiciones';
        } else {
            $termsAndCondiction = 'Terms and conditions';
        }
        if (strpos($this->storeTranslations['input_country_helper'], "Ciudad") !== false) {
            $city = "Ciudad";
        } else {
            $city = "City";
        }
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
            //'terms_and_conditions_link_text'   => $termsAndCondiction,
            'and_the'   => $this->storeTranslations['and_the'],
            'terms_and_conditions_link_src'    => 'https://epayco.com/terminos-y-condiciones-generales/',
            'personal_data_processing_link_text'    => $this->storeTranslations['personal_data_processing_link_text'],
            'personal_data_processing_link_src'    => 'https://epayco.com/tratamiento-de-datos/',
            'site_id'                          => 'epayco',
            'city'                          => $city,
            'logo' =>       $this->epayco->hooks->gateway->getGatewayIcon('logo-checkout.png'),
            'icon_info' =>       $this->epayco->hooks->gateway->getGatewayIcon('icon-info.png'),
            'icon_warning' =>       $this->epayco->hooks->gateway->getGatewayIcon('warning.png'),
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

            $checkout['token'] = $checkout['cardTokenId'] ?? $checkout['cardtokenid'] ?? '';
            
            if (!empty($checkout['token'])) {
                $this->transaction = new CreditCardTransaction($this, $order, $checkout);
                $redirect_url = get_site_url() . "/";
                $redirect_url = add_query_arg('wc-api', self::WEBHOOK_API_NAME, $redirect_url);
                $redirect_url = add_query_arg('order_id', $order_id, $redirect_url);
                $confirm_url = $redirect_url . '&confirmation=1';
                $checkout['confirm_url'] = $confirm_url;
                $checkout['response_url'] = $order->get_checkout_order_received_url();

                $response = $this->transaction->createTcPayment($order_id, $checkout);
                $response = json_decode(wp_json_encode($response), true);
                if (is_array($response) && $response['success']) {
                    $ref_payco = $response['data']['refPayco'] ?? $response['data']['ref_payco'];
                    $estado = strtolower($response['data']['estado']);

                    if (in_array($estado, ["pendiente", "pending"])) {
                        $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, [$ref_payco]);
                        $order->update_status("on-hold");
                        $this->epayco->woocommerce->cart->empty_cart();
                        $urlReceived = $order->get_checkout_order_received_url();
                        $threeDs = null;
                        if(isset($response['data']['3DS'])){
                            $public_key = $this->epayco->sellerConfig->getCredentialsPublicKeyPayment();
                            $private_key = $this->epayco->sellerConfig->getCredentialsPrivateKeyPayment();
                            $token = base64_encode($public_key.":".$private_key);
                            $json_data = $response['data'];
                            /*$json_data = json_encode([
                                "returnUrl" => $urlReceived,
                                "franquicia" => $response['data']['franquicia'],
                                "threeDs" => json_encode($response['data']['3DS']),
                                "ref_payco" => $ref_payco,
                                "cc_network_response" => $response['data']['cc_network_response'],
                                "hash" => $token??null
                            ]);*/
                            $idSessionToken = base64_encode($json_data);
                            //$urlReceived = "https://vtex.epayco.io/3ds?token=".$idSessionToken;
                            $urlReceived = "https://eks-cms-backend-platforms-service.epayco.io/3ds?token=".$idSessionToken;
                            $threeDs = json_encode($response['data']['3DS']);
                        }
                        $return = [
                            'result'   => 'success',
                            'message' => $response['data']['respuesta'],
                            'threeDs' => $threeDs,
                            'redirect' => $urlReceived,
                        ];
                    }
                    if (in_array($estado, ["aceptada", "acepted"])) {
                        $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, [$ref_payco]);
                        $order->update_status("processing");
                        $this->epayco->woocommerce->cart->empty_cart();
                        $urlReceived = $order->get_checkout_order_received_url();
                        $return = [
                            'result'   => 'success',
                            'message' => $response['data']['respuesta'],
                            'redirect' => $urlReceived,
                        ];
                    }
                    if (in_array($estado, ["rechazada", "fallida", "cancelada", "abandonada"])) {
                        $urlReceived = wc_get_checkout_url();
                        $return = [
                            'result'   => 'fail',
                            'message' => $response['data']['respuesta'],
                            'redirect' => $urlReceived,
                        ];
                    }
                    return $return;
                } else {
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
                    $processReturnFailMessage = $messageError . " " . $errorMessage;
                    if (class_exists('WC_Logger')) {
                        $logger = wc_get_logger();
                        $logger->info("token: ".$processReturnFailMessage);
                    }
                    return $this->returnFail($processReturnFailMessage, $order);
                }
            } else {
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
        $paymentInfo = json_decode(wp_json_encode($lastPaymentId), true);

        if (empty($paymentInfo)) {
            return;
        }
        $data = array(
            "filter" => array("referencePayco" => $paymentInfo),
            "success" => true
        );
        $this->transaction = new CreditCardTransaction($this, $order, []);
        //$transactionDetails = $this->transaction->sdk->transaction->get($paymentInfo);
        $transactionDetails = $this->transaction->sdk->transaction->get($data, true, "POST");
        $transactionInfo = json_decode(wp_json_encode($transactionDetails), true);

        if (empty($transactionInfo)) {
            return;
        }

        $transaction = $this->transaction->returnParameterToThankyouPage($transactionInfo, $this, $order_id);

        if (empty($transaction)) {
            return;
        }

        $this->epayco->hooks->template->getWoocommerceTemplate(
            'public/order/order-received.php',
            $transaction
        );
    }
}
