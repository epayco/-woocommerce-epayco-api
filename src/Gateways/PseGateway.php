<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Helpers\PaymentStatus;
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
        return [
            'test_mode'                        => $this->epayco->storeConfig->isTestMode(),
            'test_mode_title'                  => $this->storeTranslations['test_mode_title'],
            'test_mode_description'            => $this->storeTranslations['test_mode_description'],
            'test_mode_link_text'              => $this->storeTranslations['test_mode_link_text'],
            //'test_mode_link_src'               => $this->links['docs_integration_test'],
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
                if (in_array(strtolower($response['data']['estado']),["pendiente","pending"])) {
                    $ref_payco = $response['data']['refPayco']??$response['data']['ref_payco'];
                    $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order,[$ref_payco]);
                    $order->update_status("on-hold");
                    $this->epayco->woocommerce->cart->empty_cart();
                    /*if ($this->epayco->hooks->options->getGatewayOption($this, 'stock_reduce_mode', 'no') === 'yes') {
                            wc_reduce_stock_levels($order_id);
                            wc_increase_stock_levels($order_id);
                    }*/
                    return [
                        'result'   => 'success',
                        'redirect' => $response['data']['urlbanco'],
                    ];
                }
            }else{
                //$this->handleWithRejectPayment($response);
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
                $processReturnFailMessage = $messageError. " " . $errorMessage;
                return $this->returnFail($processReturnFailMessage, $order);

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
        }
        $paymentStatusType = PaymentStatus::getStatusType(strtolower($status));

            $transaction = [
                'status' => $status,
                'type' => "",
                'refPayco' => $ref_payco,
                'factura' => $factura,
                'descripcion' => $descripcion,
                'valor' => $valor,
                'iva' => $iva,
                'estado' => $estado,
                'respuesta' => $alert_title,
                'fecha' => $transactionDateTime,
                'currency' => $currency,
                'name' => $name,
                'card' => $card,
                'success_message' => $this->storeTranslations['success_message'],
                'error_message' => $this->storeTranslations['error_message'],
                'error_description' => $this->storeTranslations['error_description'],
                'payment_method'  => $this->storeTranslations['payment_method'],
                'statusandresponse'=> $this->storeTranslations['statusandresponse'],
                'dateandtime' => $this->storeTranslations['dateandtime'],
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
