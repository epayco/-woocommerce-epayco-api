<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Transactions\SubscriptionTransaction;
use Epayco\Woocommerce\Exceptions\InvalidCheckoutDataException;
use Epayco\Woocommerce\Helpers\Form;

if (!defined('ABSPATH')) {
    exit;
}

class SubscriptionGateway extends AbstractGateway
{
    /**
     * @const
     */
    public const ID = 'woo-epayco-subscription';

    /**
     * @const
     */
    public const CHECKOUT_NAME = 'checkout-subscription';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = 'WC_Epayco_Subscription_Gateway';

    /**
     * @const
     */
    public const LOG_SOURCE = 'Epayco_SubscriptionGateway';

    /**
     * CustomGateway constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->subscriptionGatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->subscriptionCheckout;

        $this->id        = self::ID;
        $this->icon      = $this->epayco->hooks->gateway->getGatewayIcon('icon-blue-card');
        $this->iconAdmin = $this->epayco->hooks->gateway->getGatewayIcon('icon-blue-card-admin');
        $this->title     = $this->epayco->storeConfig->getGatewayTitle($this, $this->adminTranslations['gateway_title']);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->supports = [
            'subscriptions',
            'subscription_suspension',
            'subscription_reactivation',
            'subscription_cancellation',
            'multiple_subscriptions'
        ];

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['gateway_method_title'];
        $this->method_description = $this->adminTranslations['gateway_method_description'];
        $this->discount           = $this->getActionableValue('gateway_discount', 0);
        $this->commission         = $this->getActionableValue('commission', 0);

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerAvailablePaymentGateway();

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

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_subscription_checkout',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/subscription/ep-subscription-checkout', '.js'),
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
            'public/checkouts/subscription-checkout.php',
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
            //'test_mode_link_src'               => $this->links['docs_integration_test'],
            'card_form_title'                  => $this->storeTranslations['card_form_title'],
            'card_holder_name_input_label'     => $this->storeTranslations['card_holder_name_input_label'],
            'card_holder_name_input_helper'    => $this->storeTranslations['card_holder_name_input_helper'],
            'card_number_input_label'          => $this->storeTranslations['card_number_input_label'],
            'card_number_input_helper'         => $this->storeTranslations['card_number_input_helper'],
            'card_expiration_input_label'      => $this->storeTranslations['card_expiration_input_label'],
            'card_expiration_input_helper'     => $this->storeTranslations['card_expiration_input_helper'],
            'card_expiration_input_invalid_length' => $this->storeTranslations['input_helper_message_expiration_date_invalid_value'],
            'card_security_code_input_label'   => $this->storeTranslations['card_security_code_input_label'],
            'card_security_code_input_helper'  => $this->storeTranslations['card_security_code_input_helper'],
            'card_security_code_input_invalid_length' => $this->storeTranslations['input_helper_message_security_code_invalid_length'],
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
            $checkout = $this->getCheckoutEpaycoSubscription($order);

            parent::process_payment($order_id);

            $checkout['token'] = $checkout['cardTokenId'] ?? $checkout['cardtokenid'];
            if (
                !empty($checkout['token'])
            ) {
                $this->transaction = new SubscriptionTransaction($this, $order, $checkout);
                $redirect_url =get_site_url() . "/";
                $redirect_url = add_query_arg( 'wc-api', self::WEBHOOK_API_NAME, $redirect_url );
                $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
                $confirm_url = $redirect_url.'&confirmation=1';
                $checkout['confirm_url'] = $confirm_url;
                $checkout['response_url'] = $order->get_checkout_order_received_url();
                $response = $this->transaction->createSubscriptionPayment($order_id, $checkout);
                $response = json_decode(json_encode($response), true);
                if (is_array($response) && $response['success']) {
                    $ref_payco = $response['data']['refPayco']??$response['data']['ref_payco'];
                    $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, [$ref_payco]);
                    if (in_array(strtolower($response['data']['estado']),["pendiente","pending"])) {
                        $order->update_status("on-hold");
                        $this->epayco->woocommerce->cart->empty_cart();
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
                    return [
                        'result'   => 'fail',
                        'redirect' => '',
                        'message'  => $messageError. " " . $errorMessage,
                    ];
                }
            }

            throw new InvalidCheckoutDataException('exception : Unable to process payment on ' . __METHOD__);

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
    private function getCheckoutEpaycoSubscription($order): array
    {
        $checkout = [];

        if (isset($_POST['epayco_subscription'])) {
            $checkout = Form::sanitizedPostData('epayco_subscription');
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "no");
        } else {
            $checkout = $this->processBlocksCheckoutData('epayco_subscription', Form::sanitizedPostData());
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "yes");
        }

        return $checkout;
    }

}
