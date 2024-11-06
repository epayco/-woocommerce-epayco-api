<?php

namespace Epayco\Woocommerce\Gateways;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Transactions\DaviplataTransaction;

if (!defined('ABSPATH')) {
    exit;
}

class DaviplataGateway extends AbstractGateway
{
    /**
     * ID
     *
     * @const
     */
    public const ID = 'woo-epayco-daviplata';

    /**
     * @const
     */
    public const CHECKOUT_NAME = 'checkout-daviplata';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = 'WC_Epayco_Daviplata_Gateway';

    /**
     * @const
     */

    /**
     * TicketGateway constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->daviplatatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->daviplataCheckout;

        $this->id        = self::ID;
        $this->icon      = $this->getCheckoutIcon();
        $this->iconAdmin = $this->getCheckoutIcon(true);
        $this->title     = $this->epayco->storeConfig->getGatewayTitle($this, $this->adminTranslations['gateway_title']);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['method_title'];
        $this->method_description = $this->description;

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        //$this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderThankYouPage']);
        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_API_NAME, [$this, 'webhook']);
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
            //$this->registerCheckoutScripts();
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
            'wc_epayco_daviplata_checkout',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/daviplata/ep-daviplata-checkout', '.js'),
            [
                'site_id' => $this->countryConfigs['site_id'],
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
            'public/checkouts/daviplata-checkout.php',
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
            'test_mode_title'                  => $this->storeTranslations['test_mode_title'],
            'test_mode_description'            => $this->storeTranslations['test_mode_description'],
            'test_mode'                        => $this->epayco->storeConfig->isTestMode(),
            'input_name_label'                 => $this->storeTranslations['input_name_label'],
            'input_name_helper'                => $this->storeTranslations['input_name_helper'],
            'input_email_label'                => $this->storeTranslations['input_email_label'],
            'input_email_helper'               => $this->storeTranslations['input_email_helper'],
            'input_address_label'              => $this->storeTranslations['input_address_label'],
            'input_address_helper'             => $this->storeTranslations['input_address_helper'],
            'input_ind_phone_label'            => $this->storeTranslations['input_ind_phone_label'],
            'input_ind_phone_helper'           => $this->storeTranslations['input_ind_phone_helper'],
            'person_type_label'                => $this->storeTranslations['person_type_label'],
            'input_document_label'             => $this->storeTranslations['input_document_label'],
            'input_document_helper'            => $this->storeTranslations['input_document_helper'],
            'input_country_label'              => $this->storeTranslations['input_country_label'],
            'input_country_helper'             => $this->storeTranslations['input_country_helper'],
            'site_id'                          => $this->epayco->sellerConfig->getSiteId(),
            'terms_and_conditions_label'       => $this->storeTranslations['terms_and_conditions_label'],
            'terms_and_conditions_description' => $this->storeTranslations['terms_and_conditions_description'],
            'terms_and_conditions_link_text'   => $this->storeTranslations['terms_and_conditions_link_text'],
            'terms_and_conditions_link_src'    => $this->links['epayco_terms_and_conditions'],
        ];
    }

    /**
     * Get Sdk Icon
     *
     * @return string
     */
    private function getCheckoutIcon(bool $adminVersion = false): string
    {
        $iconName = 'icon-daviplata';

        return $this->epayco->hooks->gateway->getGatewayIcon($iconName . ($adminVersion ? '-admin' : ''));
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
            $checkout = $this->getCheckoutDaviplata($order);


            $redirect_url =get_site_url() . "/";
            $redirect_url = add_query_arg( 'wc-api', self::WEBHOOK_API_NAME, $redirect_url );
            $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
            $confirm_url = $redirect_url.'&confirmation=1';
            $checkout['confirm_url'] = $confirm_url;
            $checkout['response_url'] = $order->get_checkout_order_received_url();
            $testMode = $this->epayco->storeConfig->isTestMode()??false;
            $this->transaction = new DaviplataTransaction($this, $order, $checkout);
            $response          = $this->transaction->createDaviplataPayment($order, $checkout);
            if (is_array($response) && $response['success']) {
                //$this->handleWithRejectPayment($response);
                $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, [$response['data']['refPayco']]);
                if (isset($response['data']['refPayco'])) {
                    $response['urlPayment'] = 'https://vtex.epayco.io/daviplata?refPayco='.$response['data']['refPayco'];
                    $this->epayco->hooks->order->setDaviplataMetadata($order, $response);
                    $description = sprintf(
                        "ePayco: %s <a target='_blank' href='%s'>%s</a>",
                        $this->storeTranslations['congrats_title'],
                        $response['urlPayment'],
                        $this->storeTranslations['congrats_subtitle']
                    );
                    $this->epayco->hooks->order->addOrderNote($order, $description, 1);
                }


                if (in_array(strtolower($response['data']['estatus']),["pendiente","pending"])) {
                    $order->update_status("on-hold");
                    $this->epayco->woocommerce->cart->empty_cart();
                    //$urlReceived = $order->get_checkout_order_received_url();
                    $urlReceived = $response['urlPayment'];
                    $return = [
                        'result'   => 'success',
                        'redirect' => $urlReceived,
                    ];
                    return $return;
                }
            }else{
                $messageError = $response['message']??$response['titleResponse']??'error';
                $errorMessage = "";
                if (isset($response['data']['errors'])) {
                    $errors = $response['data']['errors'];
                    foreach ($errors as $error) {
                        $errorMessage = $error['errorMessage'] . "\n";
                    }
                } elseif (isset($response['data']['error'])) {
                    $errores = $response['data']['error'];
                    foreach ($errores as $error) {
                        $errorMessage = $error['errorMessage'] . "\n";
                    }
                }elseif (isset($response['data']['errores'])) {
                    $errores = $response['data']['errores'];
                    foreach ($errores as $error) {
                        $errorMessage = $error['errorMessage'] . "\n";
                    }
                }elseif (isset($response['data']['error']['errores'])) {
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
            return [
                'result'   => 'fail',
                'redirect' => '',
                'message'  => "error en daviplata",
            ];

        }catch (\Exception $e) {
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
     * Get checkout epayco ticket
     *
     * @param $order
     *
     * @return array
     */
    private function getCheckoutDaviplata($order): array
    {
        $checkout = [];

        if (isset($_POST['epayco_daviplata'])) {
            $checkout = Form::sanitizedPostData('epayco_daviplata');
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "no");
        } else {
            $checkout = $this->processBlocksCheckoutData('epayco_daviplata', Form::sanitizedPostData());
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
        $transactionDetails  =  $this->epayco->orderMetadata->getDaviplataTransactionDetailsMeta($order);

        if (empty($transactionDetails)) {
            return;
        }

        $this->epayco->hooks->template->getWoocommerceTemplate(
            'public/order/epayco-order-received.php',
            [
                'print_daviplata_label'  => $this->storeTranslations['print_daviplata_label'],
                'print_daviplata_link'  => $this->storeTranslations['print_daviplata_link'],
                'transaction_details' => $transactionDetails,
            ]
        );
    }
}