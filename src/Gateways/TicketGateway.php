<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Exceptions\InvalidCheckoutDataException;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Transactions\TicketTransaction;

if (!defined('ABSPATH')) {
    exit;
}

class TicketGateway extends AbstractGateway
{
    /**
     * ID
     *
     * @const
     */
    public const ID = 'woo-epayco-ticket';

    /**
     * @const
     */
    public const CHECKOUT_NAME = 'checkout-ticket';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = 'WC_Epayco_Ticket_Gateway';

    /**
     * @const
     */
    public const LOG_SOURCE = 'Epayco_TicketGateway';

    const CASH_ENTITIES = [
        [
            "id" =>"EF",
            "name" =>"efecty"
        ],
        [
            "id" =>"GA",
            "name" =>"gana"
        ],
        [
            "id" =>"PR",
            "name" =>"puntored"
        ],
        [
            "id" =>"RS",
            "name" =>"redservi"
        ],
        [
            "id" =>"SR",
            "name" =>"sured"
        ]
    ];

    /**
     * TicketGateway constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->ticketGatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->ticketCheckout;

        $this->id        = self::ID;
        $this->icon      = $this->getCheckoutIcon();
        $this->iconAdmin = $this->getCheckoutIcon(true);
        $this->title     = $this->epayco->storeConfig->getGatewayTitle($this, $this->adminTranslations['gateway_title']);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['method_title'];
        $this->method_description = $this->description;
        $this->discount           = $this->getActionableValue('gateway_discount', 0);
        $this->commission         = $this->getActionableValue('commission', 0);

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderThankYouPage']);

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
            ],
            'type_payments'   => $this->generateExPaymentsFields(),
            'date_expiration' => [
                'title'       => $this->adminTranslations['date_expiration_title'],
                'type'        => 'number',
                'description' => $this->adminTranslations['date_expiration_description'],
                'default'     => EP_TICKET_DATE_EXPIRATION,
            ],
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
            'wc_epayco_ticket_checkout',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/ticket/ep-ticket-checkout', '.js'),
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
            'public/checkouts/ticket-checkout.php',
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
        $address         = $this->epayco->helpers->currentUser->getCurrentUserMeta('billing_address_1', true);
        $address2        = $this->epayco->helpers->currentUser->getCurrentUserMeta('billing_address_2', true);
        $address        .= (!empty($address2) ? ' - ' . $address2 : '');
        $country         = $this->epayco->helpers->currentUser->getCurrentUserMeta('billing_country', true);
        $address        .= (!empty($country) ? ' - ' . $country : '');
        $amountAndCurrencyRatio = $this->getAmountAndCurrency();
        return [
            'test_mode'                        => $this->epayco->storeConfig->isTestMode(),
            'test_mode_title'                  => $this->storeTranslations['test_mode_title'],
            'test_mode_description'            => $this->storeTranslations['test_mode_description'],
            'test_mode_link_text'              => $this->storeTranslations['test_mode_link_text'],
            'test_mode_link_src'               => $this->links['docs_integration_test'],
            'input_name_label'                 => $this->storeTranslations['input_name_label'],
            'input_name_helper'                => $this->storeTranslations['input_name_helper'],
            'input_address_label'              => $this->storeTranslations['input_address_label'],
            'input_address_helper'             => $this->storeTranslations['input_address_helper'],
            'input_email_label'                => $this->storeTranslations['input_email_label'],
            'input_email_helper'               => $this->storeTranslations['input_email_helper'],
            'input_ind_phone_label'            => $this->storeTranslations['input_ind_phone_label'],
            'input_ind_phone_helper'           => $this->storeTranslations['input_ind_phone_helper'],
            'person_type_label'                => $this->storeTranslations['person_type_label'],
            'input_document_label'             => $this->storeTranslations['input_document_label'],
            'input_document_helper'            => $this->storeTranslations['input_document_helper'],
            'ticket_text_label'                => $this->storeTranslations['ticket_text_label'],
            'input_table_button'               => $this->storeTranslations['input_table_button'],
            'input_helper_label'               => $this->storeTranslations['input_helper_label'],
            'terms_and_conditions_label'       => $this->storeTranslations['terms_and_conditions_label'],
            'terms_and_conditions_description' => $this->storeTranslations['terms_and_conditions_description'],
            'terms_and_conditions_link_text'   => $this->storeTranslations['terms_and_conditions_link_text'],
            'terms_and_conditions_link_src'    => $this->links['epayco_terms_and_conditions'],
            'payment_methods'                  => $this->getPaymentMethods(),
            'site_id'                          => $this->epayco->sellerConfig->getSiteId(),
            'payer_email'                      => esc_js($loggedUserEmail),
            'woocommerce_currency'             => get_woocommerce_currency(),
            'account_currency'                 => $this->epayco->helpers->country->getCountryConfigs(),
            'amount'                           => $amountAndCurrencyRatio['amount'],
            'currency_ratio'                   => $amountAndCurrencyRatio['currencyRatio'],
            'message_error_amount'             => $this->storeTranslations['message_error_amount'],
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
            $checkout = $this->getCheckoutEpaycoTicket($order);

            parent::process_payment($order_id);

            if (
                !empty($checkout['amount']) &&
                !empty($checkout['payment_method_id'])
            ) {
                $redirect_url =get_site_url() . "/";
                $redirect_url = add_query_arg( 'wc-api', self::WEBHOOK_API_NAME, $redirect_url );
                $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
                $confirm_url = $redirect_url.'&confirmation=1';
                $checkout['confirm_url'] = $confirm_url;
                $checkout['response_url'] = $order->get_checkout_order_received_url();
                $checkout['date_expiration'] = $this->settings['date_expiration'];
                $payment_method_id= $checkout["payment_method_id"]??$checkout[""]["payment_method_id"];
                $key = array_search( $payment_method_id, array_column(self::CASH_ENTITIES, 'name'));
                $checkout['paymentMethod'] = self::CASH_ENTITIES[$key]['id'];
                $this->transaction = new TicketTransaction($this, $order, $checkout);
                $response          = $this->transaction->createCashPayment($order, $checkout);

                if (is_array($response) && $response['success']) {
                    //$this->handleWithRejectPayment($response);
                    $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order,[$response['data']['refPayco']]);
                    if (isset($response['data']['token'])) {
                        $response['urlPayment'] = 'https://vtex.epayco.io/es/pin?token='.$response['data']['token'];
                        $this->epayco->hooks->order->setTicketMetadata($order, $response);
                        $description = sprintf(
                            "ePayco: %s <a target='_blank' href='%s'>%s</a>",
                            $this->storeTranslations['congrats_title'],
                            $response['urlPayment'],
                            $this->storeTranslations['congrats_subtitle']
                        );
                        $this->epayco->hooks->order->addOrderNote($order, $description, 1);
                    }


                    if (in_array(strtolower($response['data']['status']),["pendiente","pending"])) {
                        $order->update_status("on-hold");
                        $this->epayco->woocommerce->cart->empty_cart();
                        $urlReceived = $order->get_checkout_order_received_url();
                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];
                        return $return;
                    }
                }else{
                    $messageError = $response['message']?? $response['titleResponse'];
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
     * Get checkout epayco ticket
     *
     * @param $order
     *
     * @return array
     */
    private function getCheckoutEpaycoTicket($order): array
    {
        $checkout = [];

        if (isset($_POST['epayco_ticket'])) {
            $checkout = Form::sanitizedPostData('epayco_ticket');
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "no");
        } else {
            $checkout = $this->processBlocksCheckoutData('epayco_ticket', Form::sanitizedPostData());
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "yes");
        }

        return $checkout;
    }


    /**
     * Mount payment_methods field
     *
     * @return array
     */
    private function generateExPaymentsFields(): array
    {
        $paymentMethods = [
            [
                'id' => 'efecty',
                'name'              => 'Efecty',
                'thumbnail'         => 'https://secure.epayco.co/img/efecty.png'
            ],
            [
                'id' => 'gana',
                'name'              => 'Gana',
                'thumbnail'         => 'https://secure.epayco.co/img/gana_no_red.png'
            ],
            [
                'id' => 'puntored',
                'name'              => 'Puntored',
                'thumbnail'         => 'https://secure.epayco.co/img/puntored.jpg'
            ],
            [
                'id' => 'redservi',
                'name'              => 'Redservi',
                'thumbnail'         => 'https://secure.epayco.co/img/redservi.jpg'
            ],
            [
                'id' => 'sured',
                'name'              => 'Sured',
                'thumbnail'         => 'https://secure.epayco.co/img/sured.jpg'
            ],
            [
                'id' => 'pagatodo',
                'name'              => 'Pagatodo',
                'thumbnail'         => 'https://secure.epayco.co/img/pagatodo.jpg'
            ],
            [
                'id' => 'acertemos',
                'name'              => 'Acertemos',
                'thumbnail'         => 'https://secure.epayco.co/img/acertemos.jpg'
            ],
            [
                'id' => 'ganagana',
                'name'              => 'Ganagana',
                'thumbnail'         => 'https://secure.epayco.co/img/ganagana.jpg'
            ],
            [
                'id' => 'suchance',
                'name'              => 'Suchance',
                'thumbnail'         => 'https://secure.epayco.co/img/suchance.jpg'
            ],
            [
                'id' => 'jer',
                'name'              => 'Jer',
                'thumbnail'         => 'https://secure.epayco.co/img/jer.jpg'
            ],
            [
                'id' => 'laperla',
                'name'              => 'Laperla',
                'thumbnail'         => 'https://secure.epayco.co/img/laperla.jpg'
            ]

        ];
        $payment_list = [
            'type'                 => 'ep_checkbox_list',
            'title'                => $this->adminTranslations['type_payments_title'],
            'description'          => $this->adminTranslations['type_payments_description'],
            'desc_tip'             => $this->adminTranslations['type_payments_desctip'],
            'payment_method_types' => [
                'ticket'           => [
                    'label'        => $this->adminTranslations['type_payments_label'],
                    'list'         => [],
                ],
            ],
        ];

        foreach ($paymentMethods as $paymentMethod) {
            $payment_list['payment_method_types']['ticket']['list'][] = [
                'id'        => $paymentMethod['id'],
                'type'      => 'checkbox',
                'field_key' => $this->get_field_key($paymentMethod['id']),
                'value'     => $this->epayco->hooks->options->getGatewayOption($this, $paymentMethod['id'], 'yes'),
                'label'     =>  $paymentMethod['name'],
            ];
        }

        return $payment_list;
    }



    /**
     * Get Sdk Icon
     *
     * @return string
     */
    private function getCheckoutIcon(bool $adminVersion = false): string
    {
        $siteId   = strtoupper($this->epayco->sellerConfig->getSiteId());
        $iconName = ($siteId === 'MLB') ? 'icon-ticket-mlb' : 'icon-ticket';

        return $this->epayco->hooks->gateway->getGatewayIcon($iconName . ($adminVersion ? '-admin' : ''));
    }

    /**
     * Get payment methods
     *
     * @return array
     */
    private function getPaymentMethods(): array
    {
        $ticketPaymentMethods = [
            [
                'id' => 'efecty',
                'name'              => 'Efecty',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/efecty.png'
            ],
            [
                'id' => 'gana',
                'name'              => 'Gana',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/gana_no_red.png'
            ],
            [
                'id' => 'puntored',
                'name'              => 'Puntored',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/puntored.jpg'
            ],
            [
                'id' => 'redservi',
                'name'              => 'Redservi',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/redservi.jpg'
            ],
            [
                'id' => 'sured',
                'name'              => 'Sured',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/sured.jpg'
            ],
            [
                'id' => 'pagatodo',
                'name'              => 'Pagatodo',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/pagatodo.jpg'
            ],
            [
                'id' => 'acertemos',
                'name'              => 'Acertemos',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/acertemos.jpg'
            ],
            [
                'id' => 'ganagana',
                'name'              => 'Ganagana',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/ganagana.jpg'
            ],
            [
                'id' => 'suchance',
                'name'              => 'Suchance',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/suchance.jpg'
            ],
            [
                'id' => 'jer',
                'name'              => 'Jer',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/jer.jpg'
            ],
            [
                'id' => 'laperla',
                'name'              => 'Laperla',
                'status'            => 'active',
                'thumbnail'         => 'https://secure.epayco.co/img/laperla.jpg'
            ]

        ];

        if (!empty($ticketPaymentMethods)) {
            foreach ($ticketPaymentMethods as $ticketPaymentMethod) {
                if (
                    !isset($this->settings[$ticketPaymentMethod['id']]) ||
                    'yes' === $this->settings[$ticketPaymentMethod['id']]
                ) {
                    $activePaymentMethods[] = $ticketPaymentMethod;
                }
            }
        }

        sort($activePaymentMethods);


        return $this->epayco->helpers->paymentMethods->treatTicketPaymentMethods($activePaymentMethods);
    }



    /**
     * Render thank you page
     *
     * @param $order_id
     */
    public function renderThankYouPage($order_id): void
    {
        $order        = wc_get_order($order_id);
        $transactionDetails  =  $this->epayco->orderMetadata->getTicketTransactionDetailsMeta($order);

        if (empty($transactionDetails)) {
            return;
        }

        $this->epayco->hooks->template->getWoocommerceTemplate(
            'public/order/ticket-order-received.php',
            [
                'print_ticket_label'  => $this->storeTranslations['print_ticket_label'],
                'transaction_details' => $transactionDetails,
            ]
        );
    }
}
