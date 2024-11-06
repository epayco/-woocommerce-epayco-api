<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Helpers\Device;
use Epayco\Woocommerce\Sdk\EpaycoSdk;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Helpers\Numbers;
use Epayco\Woocommerce\WoocommerceEpayco;
use Epayco\Woocommerce\Interfaces\EpaycoGatewayInterface;
use Epayco\Woocommerce\Notification\NotificationFactory;
use Epayco\Woocommerce\Exceptions\RejectedPaymentException;

abstract class AbstractGateway extends \WC_Payment_Gateway implements EpaycoGatewayInterface
{
    /**
     * @const
     */
    public const ID = '';

    /**
     * @const
     */
    public const CHECKOUT_NAME = '';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = '';

    /**
     * @const
     */
    public const LOG_SOURCE = '';

    /**
     * @var string
     */
    public $iconAdmin;

    /**
     * @var WoocommerceEpayco
     */
    protected $epayco;


    /**
     * Commission
     *
     * @var int
     */
    public $commission;

    /**
     * Discount
     *
     * @var int
     */
    public $discount;

    /**
     * Expiration date
     *
     * @var int
     */
    public $expirationDate;

    /**
     * Checkout country
     *
     * @var string
     */
    public $checkoutCountry;

    /**
     * Translations
     *
     * @var array
     */
    protected $adminTranslations;

    /**
     * Translations
     *
     * @var array
     */
    protected $storeTranslations;

    /**
     * @var float
     */
    protected $ratio;

    /**
     * @var array
     */
    protected $countryConfigs;

    /**
     * @var array
     */
    protected $links;

    /**
     * Abstract Gateway constructor
     */
    public function __construct()
    {
        global $epayco;

        $this->epayco = $epayco;

        $this->checkoutCountry = $this->epayco->storeConfig->getCheckoutCountry();
        $this->countryConfigs  = $this->epayco->helpers->country->getCountryConfigs();
        $this->links           = $this->epayco->helpers->links->getLinks();

        $this->has_fields = true;
        $this->supports   = ['products', 'refunds'];

        $this->init_settings();
        $this->sdk  = $this->getSdkInstance();
    }

    /**
     * Get SDK instance
     */
    public function getSdkInstance():EpaycoSdk
    {

        $public_key = $this->epayco->sellerConfig->getCredentialsPublicKeyPayment();
        $private_key = $this->epayco->sellerConfig->getCredentialsPrivateKeyPayment();
        $pCustId = $this->epayco->sellerConfig->getCredentialsPCustId();
        $pKey = $this->epayco->sellerConfig->getCredentialsPkey();
        $isTestMode = $this->epayco->storeConfig->isTestMode()?"true":"false";
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
     * Process blocks checkout data
     *
     * @param $prefix
     * @param $postData
     *
     * @return array
     */
    public function processBlocksCheckoutData($prefix, $postData): array
    {
        $checkoutData = [];

        foreach ($postData as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $newKey = substr($key, strlen($prefix));
                $checkoutData[$newKey] = $value;
            }
        }

        return $checkoutData;
    }

    public function saveOrderPaymentsId(string $orderId)
    {
        $order = wc_get_order($orderId);
        $paymentIds = Form::sanitizedGetData('payment_id');

        if ($paymentIds) {
            $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, explode(',', $paymentIds));
            return;
        }
    }

    /**
     * Init form fields for checkout configuration
     *
     * @return void
     */
    public function init_form_fields(): void
    {
        $this->form_fields = [];
    }

    /**
     * Add a "missing credentials" notice into the $form_fields array if there ir no credentials configured.
     * Returns true when the notice is added to the array, and false otherwise.
     *
     * @return bool
     */
    protected function addMissingCredentialsNoticeAsFormField(): bool
    {

        return false;
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
        if ($this->canAdminLoadScriptsAndStyles($gatewaySection)) {
            $this->registerAdminScripts();
        }

        if ($this->canCheckoutLoadScriptsAndStyles()) {
            $this->registerCheckoutScripts();
        }
    }

    /**
     * Register admin scripts
     *
     * @return void
     */
    public function registerAdminScripts()
    {
        $this->epayco->hooks->scripts->registerAdminScript(
            'wc_epayco_admin_components',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/admin/ep-admin-configs', '.js')
        );

        $this->epayco->hooks->scripts->registerAdminStyle(
            'wc_epayco_admin_components',
            $this->epayco->helpers->url->getPluginFileUrl('assets/css/admin/ep-admin-configs', '.css')
        );
    }

    /**
     * Register checkout scripts
     *
     * @return void
     */
    public function registerCheckoutScripts(): void
    {


        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_token_sdk',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/creditcard/library', '.js')
        );

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_checkout_components',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/ep-plugins-components', '.js'),
            [
                'ep_json_url' => EP_PLUGIN_URL,
                'lang' => substr(get_locale(), 0, 2)
            ]
        );

        $this->epayco->hooks->scripts->registerCheckoutStyle(
            'wc_epayco_checkout_components',
            $this->epayco->helpers->url->getPluginFileUrl('assets/css/checkouts/ep-plugins-components', '.css')
        );

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_checkout_update',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/ep-checkout-update', '.js')
        );
    }

    /**
     * Render gateway checkout template
     *
     * @return void
     */
    public function payment_fields(): void
    {
    }

    /**
     * Validate gateway checkout form fields
     *
     * @return bool
     */
    public function validate_fields(): bool
    {
        return true;
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

        $isProductionMode = $this->epayco->storeConfig->getProductionMode();

        $this->epayco->orderMetadata->setIsProductionModeData($order, $isProductionMode);
        $this->epayco->orderMetadata->setUsedGatewayData($order, get_class($this)::ID);


        return [];
    }

    /**
     * Receive gateway webhook notifications
     *
     * @return void
     */
    public function webhook(): void
    {
        global $woocommerce;
        $order_id_info = sanitize_text_field($_GET['order_id']);
        $order_id_explode = explode('=',$order_id_info);
        $order_id_rpl  = str_replace('?ref_payco','',$order_id_explode);
        $order_id = $order_id_rpl[0];
        $order = new \WC_Order($order_id);
        $data = Form::sanitizedGetData();
        $params = $data??$_POST;
        $params = $_POST;
        $x_signature = sanitize_text_field($params['x_signature']);
        $x_cod_transaction_state = sanitize_text_field($params['x_cod_transaction_state']);
        $x_ref_payco = sanitize_text_field($params['x_ref_payco']);
        $x_transaction_id = sanitize_text_field($params['x_transaction_id']);
        $x_amount = sanitize_text_field($params['x_amount']);
        $x_currency_code = sanitize_text_field($params['x_currency_code']);
        $x_test_request = trim(sanitize_text_field($params['x_test_request']));
        $x_approval_code = trim(sanitize_text_field($params['x_approval_code']));
        $x_franchise = trim(sanitize_text_field($params['x_franchise']));
        $x_fecha_transaccion = trim(sanitize_text_field($params['x_fecha_transaccion']));
        if ($order_id != "" && $x_ref_payco != "") {
            $authSignature = $this->authSignature($x_ref_payco, $x_transaction_id, $x_amount, $x_currency_code);
        }
        $isTestPluginMode = $this->epayco->storeConfig->isTestMode();
        $modo = $isTestPluginMode?'Prueba':'Producción';
        $current_state = $order->get_status();
        if(floatval($order->get_total()) == floatval($x_amount)){
            if($isTestPluginMode){
                $validation = true;
            }
            if(!$isTestPluginMode ){
                if($x_cod_transaction_state == 1){
                    $validation = true;
                }else{
                    if($x_cod_transaction_state != 1){
                        $validation = true;
                    }else{
                        $validation = false;
                    }
                }

            }
        }else{
            $validation = false;
        }

        if($authSignature == $x_signature && $validation){
            switch ($x_cod_transaction_state) {
                case 1: {
                    $message = 'Pago Proccesado ' .$x_ref_payco;
                    if($current_state !== "processing"){
                        if($current_state == "failed" ||
                            $current_state == "canceled"
                        ){
                            /*wc_reduce_stock_levels($order_id);
                            wc_increase_stock_levels($order_id);*/
                        }
                        $order->update_status("processing");
                    }

                }break;
                case 2:
                case 4:
                case 10:
                case 11:{
                    $message = 'Pago Cancelado ' .$x_ref_payco;
                    $order->update_status('cancelled');
                }break;
                case 3:
                case 7:{
                    $message = 'Pago Pendiente ' .$x_ref_payco;
                    $order->update_status("on-hold");
                }break;
                case 6: {
                    $message = 'Pago Reversada ' .$x_ref_payco;
                    $order->update_status('refunded');
                    $order->add_order_note('Pago Reversado');
                    $this->restore_order_stock($order->get_id());
                    echo "6";
                } break;
                default: {
                    $message = 'Pago fallido ' .$x_ref_payco;
                    $order->update_status('failed');
                    $order->add_order_note('Pago fallido o abandonado');
                }
            }
            update_post_meta( $order->get_id(), 'refPayco', esc_attr($x_ref_payco));
            update_post_meta( $order->get_id(), 'modo', esc_attr($modo));
            update_post_meta( $order->get_id(), 'fecha', esc_attr($x_fecha_transaccion));
            update_post_meta( $order->get_id(), 'franquicia', esc_attr($x_franchise));
            update_post_meta( $order->get_id(), 'autorizacion', esc_attr($x_approval_code));
        }else{
            $message = 'Firma no valida';
        }
        echo $message;
        die();
    }

    public function authSignature($x_ref_payco, $x_transaction_id, $x_amount, $x_currency_code){
        $pCustId = $this->epayco->sellerConfig->getCredentialsPCustId();
        $pKey = $this->epayco->sellerConfig->getCredentialsPkey();
        $signature = hash('sha256',
            trim($pCustId).'^'
            .trim($pKey).'^'
            .$x_ref_payco.'^'
            .$x_transaction_id.'^'
            .$x_amount.'^'
            .$x_currency_code
        );
        return $signature;
    }

    /**
     * Verify if the gateway is available
     *
     * @return bool
     */
    public static function isAvailable(): bool
    {
        return true;
    }

    /**
     * Check if admin scripts and styles can be loaded
     *
     * @param string $gatewaySection
     *
     * @return bool
     */
    public function canAdminLoadScriptsAndStyles(string $gatewaySection): bool
    {
        return $this->epayco->hooks->admin->isAdmin() && ($this->epayco->helpers->url->validatePage('wc-settings') &&
            $this->epayco->helpers->url->validateSection($gatewaySection)
        );
    }

    /**
     * Check if admin scripts and styles can be loaded
     *
     * @return bool
     */
    public function canCheckoutLoadScriptsAndStyles(): bool
    {
        return $this->epayco->hooks->checkout->isCheckout() &&
            $this->epayco->hooks->gateway->isEnabled($this) &&
            !$this->epayco->helpers->url->validateQueryVar('order-received');
    }




    /**
     * Process if result is fail
     *
     * @param \Exception $e
     * @param string $message
     * @param string $source
     * @param array $context
     * @param bool $notice
     *
     * @return array
     */
    public function processReturnFail(\Exception $e, string $message, string $source, array $context = [], bool $notice = false): array
    {

        $errorMessages = [
            "Invalid test user email" => $this->epayco->storeTranslations->commonMessages['invalid_users'],
            "Invalid users involved" => $this->epayco->storeTranslations->commonMessages['invalid_users'],
            "Invalid operators users involved" => $this->epayco->storeTranslations->commonMessages['invalid_operators'],
            "exception" => $this->epayco->storeTranslations->buyerRefusedMessages['buyer_default'],
            "400" => $this->epayco->storeTranslations->buyerRefusedMessages['buyer_default'],
        ];

        foreach ($errorMessages as $keyword => $replacement) {
            if (strpos($message, $keyword) !== false) {
                $message = $replacement;
                break;
            }
        }

        if ($notice) {
            $this->epayco->helpers->notices->storeNotice($message, 'error');
        }

        return [
            'result'   => 'fail',
            'redirect' => '',
            'message'  => $message,
        ];
    }

    /**
     * Register plugin and commission to WC_Cart fees
     *
     * @return void
     */
    public function registerDiscountAndCommissionFeesOnCart()
    {
        if ($this->epayco->hooks->checkout->isCheckout()) {
            $this->epayco->helpers->cart->addDiscountAndCommissionOnFees($this);
        }
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
     * @return string
     */
    public function getFeeTitle(): string
    {
        if ($this->epayco->helpers->cart->isAvailable()) {
            $discount = $this->epayco->helpers->cart->calculateSubtotalWithDiscount($this);
            $commission = $this->epayco->helpers->cart->calculateSubtotalWithCommission($this);

            return $this->epayco->hooks->gateway->buildTitleWithDiscountAndCommission(
                $discount,
                $commission,
                $this->epayco->storeTranslations->commonCheckout['discount_title'],
                $this->epayco->storeTranslations->commonCheckout['fee_title']
            );
        }

        return '';
    }

    /**
     * Get actionable component value
     *
     * @param string $optionName
     * @param mixed $default
     *
     * @return string
     */
    public function getActionableValue(string $optionName, $default): string
    {
        $active = $this->epayco->hooks->options->getGatewayOption($this, "{$optionName}_checkbox");

        if ($active === 'yes') {
            return $this->epayco->hooks->options->getGatewayOption($this, $optionName, $default);
        }

        return $default;
    }

    /**
     * Get fee text
     *
     * @param string $text
     * @param string $feeName
     * @param float $feeValue
     *
     * @return string
     */
    public function getFeeText(string $text, string $feeName, float $feeValue): string
    {
        $total = Numbers::formatWithCurrencySymbol($this->epayco->helpers->currency->getCurrencySymbol(), $feeValue);
        return "$text $feeName% = $total";
    }

    /**
     * Get amount
     *
     * @return float
     */
    protected function getAmount(): float
    {
        // WC_Cart is null when blocks is loaded on the admin
        if (!$this->epayco->helpers->cart->isAvailable()) {
            return 0.00;
        }

        return $this->epayco->helpers->cart->calculateTotalWithDiscountAndCommission($this);
    }

    /**
     * Get discount config field
     *
     * @return array
     */
    public function getDiscountField(): array
    {
        return [
            'type'              => 'ep_actionable_input',
            'title'             => $this->adminTranslations['discount_title'],
            'input_type'        => 'number',
            'description'       => $this->adminTranslations['discount_description'],
            'checkbox_label'    => $this->adminTranslations['discount_checkbox_label'],
            'default'           => '0',
            'custom_attributes' => [
                'step' => '0.01',
                'min'  => '0',
                'max'  => '99',
            ],
        ];
    }

    /**
     * Get commission config field
     *
     * @return array
     */
    public function getCommissionField(): array
    {
        return [
            'type'              => 'ep_actionable_input',
            'title'             => $this->adminTranslations['commission_title'],
            'input_type'        => 'number',
            'description'       => $this->adminTranslations['commission_description'],
            'checkbox_label'    => $this->adminTranslations['commission_checkbox_label'],
            'default'           => '0',
            'custom_attributes' => [
                'step' => '0.01',
                'min'  => '0',
                'max'  => '99',
            ],
        ];
    }

    /**
     * Generate credits toggle switch component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_toggle_switch_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/toggle-switch.php',
            [
                'field_key'   => $this->get_field_key($key),
                'field_value' => $this->epayco->hooks->options->getGatewayOption($this, $key, $settings['default']),
                'settings'    => $settings,
            ]
        );
    }

    /**
     * Generate credits toggle switch component
     *
     * @param string $key
     * @param array  $settings
     *
     * @return string
     */
    public function generate_ep_checkbox_list_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/checkbox-list.php',
            [
                'settings' => $settings,
            ]
        );
    }

    /**
     * Generate credits header component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_config_title_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/config-title.php',
            [
                'field_key'   => $this->get_field_key($key),
                'field_value' => null,
                'settings'    => $settings,
            ]
        );
    }

    /**
     * Generating credits actionable input component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_actionable_input_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/actionable-input.php',
            [
                'field_key'          => $this->get_field_key($key),
                'field_key_checkbox' => $this->get_field_key($key . '_checkbox'),
                'field_value'        => $this->epayco->hooks->options->getGatewayOption($this, $key),
                'enabled'            => $this->epayco->hooks->options->getGatewayOption($this, $key . '_checkbox'),
                'custom_attributes'  => $this->get_custom_attribute_html($settings),
                'settings'           => $settings,
                'allowedHtmlTags'    => $this->epayco->helpers->strings->getAllowedHtmlTags(),
            ]
        );
    }

    /**
     * Generating credits card info component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_card_info_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/card-info.php',
            [
                'field_key'   => $this->get_field_key($key),
                'field_value' => null,
                'settings'    => $settings,
            ]
        );
    }

    /**
     * Generating credits preview component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_preview_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/preview.php',
            [
                'field_key'   => $this->get_field_key($key),
                'field_value' => null,
                'settings'    => $settings,
            ]
        );
    }

      /**
     * Generating support link component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_support_link_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/support-link.php',
            [
                'field_key'   => $this->get_field_key($key),
                'field_value' => null,
                'settings'    => $settings,
            ]
        );
    }

      /**
     * Generating tooltip selection component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_tooltip_selection_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/tooltip-selection.php',
            [
                'field_key'   => $this->get_field_key($key),
                'field_value' => null,
                'settings'    => $settings,
            ]
        );
    }

    /**
     * Generating credits checkout example component
     *
     * @param string $key
     * @param array $settings
     *
     * @return string
     */
    public function generate_ep_credits_checkout_example_html(string $key, array $settings): string
    {
        return $this->epayco->hooks->template->getWoocommerceTemplateHtml(
            'admin/components/credits-checkout-example.php',
            [
                'field_key'   => $this->get_field_key($key),
                'field_value' => null,
                'settings'    => $settings,
            ]
        );
    }


    /**
     * Update Option
     *
     * @param string $key key.
     * @param string $value value.
     *
     * @return bool
     */
    public function update_option($key, $value = ''): bool
    {
        if ($key === 'enabled' && $value === 'yes') {
            $publicKey   = $this->epayco->sellerConfig->getCredentialsPublicKey();
            $accessToken = $this->epayco->sellerConfig->getCredentialsAccessToken();

            if (empty($publicKey) || empty($accessToken)) {

                echo wp_json_encode(
                    array(
                        'data'    => $this->epayco->adminTranslations->gatewaysSettings['empty_credentials'],
                        'success' => false,
                    )
                );

                die();
            }
        }

        return parent::update_option($key, $value);
    }

    /**
     * Handle With Rejectec Payment Status
     *
     * @param $response
     *
     */
    public function handleWithRejectPayment($response)
    {
        if ($response['status'] === 'rejected') {
            $statusDetail = $response['status_detail'];

            $errorMessage = $this->getRejectedPaymentErrorMessage($statusDetail);

            throw new RejectedPaymentException($errorMessage);
        }
    }

    /**
     * Get payment rejected error message
     *
     * @param string $statusDetail statusDetail.
     *
     * @return string
     */
    public function getRejectedPaymentErrorMessage($statusDetail)
    {
        return $this->epayco->storeTranslations->buyerRefusedMessages['buyer_' . $statusDetail] ??
            $this->epayco->storeTranslations->buyerRefusedMessages['buyer_default'];
    }

    /**
     * Get url setting
     *
     * @return string
     */
    public function get_settings_url()
    {
        return $this->links['admin_settings_page'];
    }

}
