<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Transactions\PseTransaction;
use Epayco\Woocommerce\Exceptions\ResponseStatusException;
use Epayco\Woocommerce\Exceptions\InvalidCheckoutDataException;

if (!defined('ABSPATH')) {
    exit;
}

class PseGateway extends AbstractGateway
{
    /**
     * ID
     *
     * @const
     */
    public const ID = 'woo-epayco-pse';

    /**
     * @const
     */
    public const CHECKOUT_NAME = 'checkout-pse';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = 'WC_Epayco_Pse_Gateway';

    /**
     * @const
     */
    public const LOG_SOURCE = 'Epayco_PseGateway';

    /**
     * PseGateway constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->pseGatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->pseCheckout;

        $this->id    = self::ID;
        $this->icon  = $this->epayco->hooks->gateway->getGatewayIcon('icon-pse');
        $this->iconAdmin = $this->epayco->hooks->gateway->getGatewayIcon('icon-pse');
        $this->title = $this->epayco->storeConfig->getGatewayTitle($this, $this->adminTranslations['gateway_title']);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['method_title'];
        $this->method_description = $this->description;
        $this->discount           = $this->getActionableValue('gateway_discount', 0);
        $this->commission         = $this->getActionableValue('commission', 0);

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);

        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_API_NAME, [$this, 'webhook']);
        $this->epayco->hooks->cart->registerCartCalculateFees([$this, 'registerDiscountAndCommissionFeesOnCart']);

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
            'config_header' => [
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
                    'enabled'  => $this->adminTranslations['enabled_enabled'],
                    'disabled' => $this->adminTranslations['enabled_disabled'],
                ],
            ],
            'title' => [
                'type'        => 'text',
                'title'       => $this->adminTranslations['title_title'],
                'description' => $this->adminTranslations['title_description'],
                'default'     => $this->adminTranslations['title_default'],
                'desc_tip'    => $this->adminTranslations['title_desc_tip'],
                'class'       => 'limit-title-max-length',
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
            'wc_epayco_pse_checkout',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/pse/ep-pse-checkout', '.js'),
            [
                'financial_placeholder' => $this->storeTranslations ['financial_placeholder'],
                'pse' => 'epayco payment'
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
            'public/checkouts/pse-checkout.php',
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
        $currentUser     = $this->epayco->helpers->currentUser->getCurrentUser();
        $loggedUserEmail = ($currentUser->ID != 0) ? $currentUser->user_email : null;
        $amountAndCurrencyRatio = $this->getAmountAndCurrency();
        return [
            'amount'                           => $amountAndCurrencyRatio['amount'],
            'message_error_amount'             => $this->storeTranslations['message_error_amount'],
            'test_mode'                        => $this->epayco->storeConfig->isTestMode(),
            'test_mode_title'                  => $this->storeTranslations['test_mode_title'],
            'test_mode_description'            => $this->storeTranslations['test_mode_description'],
            'test_mode_link_text'              => $this->storeTranslations['test_mode_link_text'],
            'test_mode_link_src'               => $this->links['docs_integration_test'],
            'input_name_label'                 => $this->storeTranslations['input_name_label'],
            'input_name_helper'                => $this->storeTranslations['input_name_helper'],
            'input_email_label'                => $this->storeTranslations['input_email_label'],
            'input_email_helper'               => $this->storeTranslations['input_email_helper'],
            'input_address_label'              => $this->storeTranslations['input_address_label'],
            'input_address_helper'             => $this->storeTranslations['input_address_helper'],
            'input_document_label'             => $this->storeTranslations['input_document_label'],
            'input_document_helper'            => $this->storeTranslations['input_document_helper'],
            'input_ind_phone_label'            => $this->storeTranslations['input_ind_phone_label'],
            'input_ind_phone_helper'           => $this->storeTranslations['input_ind_phone_helper'],
            'input_country_label'              => $this->storeTranslations['input_country_label'],
            'input_country_helper'             => $this->storeTranslations['input_country_helper'],
            'person_type_label'                => $this->storeTranslations['person_type_label'],
            'financial_institutions'           => json_encode($this->getFinancialInstitutions()),
            'financial_institutions_label'     => $this->storeTranslations['financial_institutions_label'],
            'financial_institutions_helper'    => $this->storeTranslations['financial_institutions_helper'],
            'financial_placeholder'            => $this->storeTranslations['financial_placeholder'],
            'site_id'                          => $this->epayco->sellerConfig->getSiteId(),
            'terms_and_conditions_label'       => $this->storeTranslations['terms_and_conditions_label'],
            'terms_and_conditions_description' => $this->storeTranslations['terms_and_conditions_description'],
            'terms_and_conditions_link_text'   => $this->storeTranslations['terms_and_conditions_link_text'],
            'terms_and_conditions_link_src'    => $this->links['epayco_terms_and_conditions'],
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
        $order    = wc_get_order($order_id);
        try {
            parent::process_payment($order_id);

            $checkout = $this->processBlocksCheckoutData('epayco_pse', Form::sanitizedPostData());
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "yes");

            //$this->validateRulesPse($checkout);
            $this->transaction = new PseTransaction($this, $order, $checkout);
            $redirect_url =get_site_url() . "/";
            $redirect_url = add_query_arg( 'wc-api', self::WEBHOOK_API_NAME, $redirect_url );
            $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
            $confirm_url = $redirect_url.'&confirmation=1';
            $checkout['confirm_url'] = $confirm_url;
            $checkout['response_url'] = $order->get_checkout_order_received_url();
            $response = $this->transaction->createPsePayment($order_id, $checkout);
            $response = json_decode(json_encode($response), true);
            if (is_array($response) && $response['success']) {
                //$this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, [$response['id']]);
                //$this->handleWithRejectPayment($response);
                if (in_array(strtolower($response['data']['estado']),["pendiente","pending"])) {
                    $ref_payco = $response['data']['refPayco']??$response['data']['ref_payco'];
                    $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order,[$ref_payco]);
                    $order->update_status("on-hold");
                    $this->epayco->woocommerce->cart->empty_cart();
                    /*if ($this->epayco->hooks->options->getGatewayOption($this, 'stock_reduce_mode', 'no') === 'yes') {
                            wc_reduce_stock_levels($order_id);
                            wc_increase_stock_levels($order_id);
                    }*/
                    //$this->epayco->hooks->order->addOrderNote($order, $this->storeTranslations['customer_not_paid']);
                    return [
                        'result'   => 'success',
                        'redirect' => $response['data']['urlbanco'],
                    ];
                }
            }else{
                $messageError = $response['message']?? $response['titleResponse'];
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
                }elseif (isset($response['data']['error'])) {
                    $errores = $response['data']['error'];
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
            //throw new InvalidCheckoutDataException('exception : Unable to process payment on ' . __METHOD__);
        } catch (\Exception $e) {
            return $this->processReturnFail(
                $e,
                $e->getMessage(),
                self::LOG_SOURCE,
                (array)$order,
                true
            );
        }
    }


    /**
     * Get payment methods
     *
     * @return array
     */
    private function getFinancialInstitutions(): array
    {
        //$test = $this->sdk->test;
        $test = $this->epayco->storeConfig->isTestMode();
        $bancos = $this->sdk->bank->pseBank($test);
        if(isset($bancos) && isset($bancos->data) ){
            $banks = (array) $bancos->data;
            $convertedBanks = array();
            foreach ($banks as $bank) {
                $convertedBanks[] = array(
                    'id' => $bank->bankCode,
                    'description' => $bank->bankName
                );
            }
        }else{
            $convertedBanks[] =['id' => 0, 'description' => "Selecciona el banco"];
            $convertedBanks[] =['id' => 1, 'description' => "nequi"];
        }


            return $convertedBanks;
    }

    /**
     * Verify if the gateway is available
     *
     * @return bool
     */
    public static function isAvailable(): bool
    {
        global $epayco;

        $siteId  = $epayco->sellerConfig->getSiteId();
        $country = $epayco->helpers->country->getWoocommerceDefaultCountry();

        if ($siteId === 'MCO' || ($siteId === '' && $country === 'CO')) {
            return true;
        }

        return false;
    }


}
