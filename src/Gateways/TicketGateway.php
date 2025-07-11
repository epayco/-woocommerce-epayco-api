<?php

namespace Epayco\Woocommerce\Gateways;

use Exception;
use Epayco\Woocommerce\Exceptions\InvalidCheckoutDataException;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Transactions\TicketTransaction;
use WP_User;

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
    public const WEBHOOK_API_NAME = 'WC_WooEpayco_Ticket_Gateway';

    /**
     * @const
     */
    public const WEBHOOK_DONWLOAD = 'Donwload';

    /**
     * @const
     */
    public const LOG_SOURCE = 'Epayco_TicketGateway';

    const CASH_ENTITIES = [
        [
            "id" => "SR",
            "name" => "Su Red"
        ],
        [
            "id" => "EF",
            "name" => "Efecty"
        ],
        [
            "id" => "GA",
            "name" => "Gana"
        ],
        [
            "id" => "PR",
            "name" => "Punto Red"
        ],
        [
            "id" => "RS",
            "name" => "Red Servi"
        ],

        [
            'id' => 'SR',
            'name' => 'Punto Red',
        ],
        [
            'id' => 'SR',
            'name' => 'Red Servicios del Cesar',
        ],
        [
            'id' => 'SR',
            'name' => 'Apuestas Cucuta 75',
        ],
        [
            'id' => 'SR',
            'name' => 'SuChance'
        ],
        [
            'id' => 'SR',
            'name' => 'La Perla'
        ],
        [
            'id' => 'SR',
            'name' => 'Jer'
        ],
        [
            'id' => 'SR',
            'name' => 'Paga Todo'
        ],
        [
            'id' => 'SR',
            'name' => 'Acertemos'
        ],
        [
            'id' => 'SR',
            'name'  => 'Gana Gana',
        ],
    ];

    /**
     * TicketGateway constructor
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->ticketGatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->ticketCheckout;

        $this->id        = self::ID;
        //$this->icon      = $this->getCheckoutIcon();
        //$this->iconAdmin = $this->getCheckoutIcon(true);
        // $this->icon      = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/PaymentCASH.png';
        $this->iconAdmin = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/efectivo.png';
        $defaultTitle = (substr(get_locale(), 0, 2) === 'es') ? 'Efectivo' : 'Cash';
        $this->title = $this->epayco->storeConfig->getGatewayTitle($this, $defaultTitle);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['method_title'];
        $this->method_description = $this->description;

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderThankYouPage']);

        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_DONWLOAD, [$this, 'validate_epayco_request']);
        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_API_NAME, [$this, 'webhook']);
        //$this->epayco->hooks->cart->registerCartCalculateFees([$this, 'registerDiscountAndCommissionFeesOnCart']);

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
            ? 'Paga en efectivo desde puntos autorizados.'
            : 'Pay in cash at authorized points.';

        return sprintf(
            '<div class="epayco-title-wrapper">
                <img class="epayco-brand-icons" height="32" src="https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/efectivo_transaparente.png" alt="ePayco Efectivo" />
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
                    'enabled'  => $this->adminTranslations['enabled_enabled'],
                    'disabled' => $this->adminTranslations['enabled_disabled'],
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
            'type_payments'   => $this->generateExPaymentsFields(),
            /*'date_expiration' => [
                'title'       => $this->adminTranslations['date_expiration_title'],
                'type'        => 'number',
                'description' => $this->adminTranslations['date_expiration_description'],
                'default'     => EP_TICKET_DATE_EXPIRATION,
            ],*/
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
            'wc_epayco_ticket_page',
            $this->epayco->helpers->url->getJsAsset('checkouts/ticket/ep-ticket-page')
        );

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_ticket_elements',
            $this->epayco->helpers->url->getJsAsset('checkouts/ticket/ep-ticket-elements')
        );

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_ticket_checkout',
            $this->epayco->helpers->url->getJsAsset('checkouts/ticket/ep-ticket-checkout'),
            [
                'site_id' => '',
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
            'public/checkout/ticket-checkout.php',
            $this->getPaymentFieldsParams()
        );
    }

    /**
     * Get Payment Fields params
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getPaymentFieldsParams(): array
    {
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
            //'test_mode_link_src'               => $this->links['docs_integration_test'],
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
            'ticket_text_label'                => $this->storeTranslations['ticket_text_label'],
            'input_table_button'               => $this->storeTranslations['input_table_button'],
            'payment_methods'                  => $this->getPaymentMethods(),
            'input_helper_label'               => $this->storeTranslations['input_helper_label'],
            'terms_and_conditions_label'       => $this->storeTranslations['terms_and_conditions_label'],
            'terms_and_conditions_description' => $this->storeTranslations['terms_and_conditions_description'],
            'terms_and_conditions_link_text'   => $this->storeTranslations['terms_and_conditions_link_text'],
            'and_the'   => $this->storeTranslations['and_the'],
            'terms_and_conditions_link_src'    => 'https://epayco.com/terminos-y-condiciones-generales/',
            'personal_data_processing_link_text'    => $this->storeTranslations['personal_data_processing_link_text'],
            'personal_data_processing_link_src'    => 'https://epayco.com/tratamiento-de-datos/',
            'site_id'                          => '',
            'city'                          => $city,
            'customer_title'              => $this->storeTranslations['customer_title'],
            'logo' =>       $this->epayco->hooks->gateway->getGatewayIcon('logo-checkout.png'),
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
            $checkout = $this->getCheckoutEpaycoTicket($order);

            parent::process_payment($order_id);

            if (
                !empty($checkout['payment_method_id'])
            ) {
                $redirect_url = get_site_url() . "/";
                $redirect_url = add_query_arg('wc-api', self::WEBHOOK_API_NAME, $redirect_url);
                $redirect_url = add_query_arg('order_id', $order_id, $redirect_url);
                $confirm_url = $redirect_url . '&confirmation=1';
                $checkout['confirm_url'] = $confirm_url;
                $checkout['response_url'] = $order->get_checkout_order_received_url();
                //$checkout['date_expiration'] = $this->settings['date_expiration'];
                $checkout["date_expiration"] = '9';
                $payment_method_id = $checkout["payment_method_id"] ?? $checkout[""]["payment_method_id"];
                $key = array_search($payment_method_id, array_column(self::CASH_ENTITIES, 'name'));
                $checkout['paymentMethod'] = self::CASH_ENTITIES[$key]['id'];
                $this->transaction = new TicketTransaction($this, $order, $checkout);
                $response          = $this->transaction->createCashPayment($order, $checkout);
                if (is_array($response) && $response['success']) {
                    $epaycoOrder = [
                        'refPayco'  => $response['data']['refPayco'],
                        'pin'  => $response['data']['pin'],
                        'codeProject'  => $response['data']['codeProject'],
                        'expirationDate' => $response['data']['expirationDate']
                    ];
                    $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order, $epaycoOrder);

                    /*if (isset($response['data']['token'])) {
                        $response['urlPayment'] = 'https://vtex.epayco.io/es/pin?token='.$response['data']['token'];
                        $this->epayco->hooks->order->setTicketMetadata($order, $response);
                        $description = sprintf(
                            "ePayco: %s <a target='_blank' href='%s'>%s</a>",
                            $this->storeTranslations['congrats_title'],
                            $response['urlPayment'],
                            $this->storeTranslations['congrats_subtitle']
                        );
                        $this->epayco->hooks->order->addOrderNote($order, $description, 1);
                    }*/


                    if (in_array(strtolower($response['data']['status']), ["pendiente", "pending"])) {
                        $order->update_status("on-hold");
                        $this->epayco->woocommerce->cart->empty_cart();
                        $urlReceived = $order->get_checkout_order_received_url();
                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];
                        return $return;
                    }
                } else {
                    $messageError = $response['message'] ?? $response['titleResponse'];
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
                    } elseif (isset($response['data']['errores'])) {
                        $errores = $response['data']['errores'];
                        foreach ($errores as $error) {
                            $errorMessage = $error['errorMessage'] . "\n";
                        }
                    } elseif (isset($response['data']['error']['errores'])) {
                        $errores = $response['data']['error']['errores'];
                        foreach ($errores as $error) {
                            $errorMessage = $error['errorMessage'] . "\n";
                        }
                    }
                    $processReturnFailMessage = $messageError . " " . $errorMessage;
                    return $this->returnFail($processReturnFailMessage, $order);
                }
            } else {
                throw new InvalidCheckoutDataException('exception : Unable to process payment on ' . __METHOD__);
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
                'id' => 'sured',
                'name' => 'Su Red',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Sured.png')
            ],
            [
                'id' => 'pagatodo',
                'name' => 'Paga Todo',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Pagatodo.png')
            ],
            [
                'id' => 'gana',
                'name' => 'Gana',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/gana.png'
            ],
            [
                'id' => 'acertemos',
                'name' => 'Acertemos',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/Acertemos.png'
            ],
            [
                'id' => 'ganagana',
                'name' => 'Gana Gana',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/Ganagana.png'
            ],
            [
                'id' => 'suchance',
                'name' => 'SuChance',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/SuChance.png'
            ],
            // [
            //     'id' => 'sured',
            //     'name' => 'Red de servicios',
            //     'status' => 'active',
            //     'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Reddeservicios.png')
            // ],
            [
                'id' => 'redservicioscesar',
                'name' => 'Red Servicios del Cesar',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('RedServiciosCesar.png')
            ],
            // [
            //     'id' => 'sured',
            //     'name' => 'Apuestas cucuta',
            //     'status' => 'active',
            //     'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Apuestascucuta.png')
            // ],
            [
                'id' => 'apuestascucuta',
                'name' => 'Apuestas Cúcuta 75',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Apuestascucuta.png')
            ],
            [
                'id' => 'jer',
                'name' => 'Jer',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/Jer.png'
            ],
            [
                'id' => 'laperla',
                'name' => 'La Perla',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/La%20perla.png'
            ],
            [
                'id' => 'efecty',
                'name' => 'Efecty',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/Efecty.png'
            ],
            // [
            //     'id' => 'sured',
            //     'name' => 'Punto red',
            //     'status' => 'active',
            //     'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Puntored.png')
            // ],
            [
                'id' => 'puntored',
                'name' => 'Punto Red',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/Punto%20red.png'
            ],
            [
                'id' => 'redservi',
                'name' => 'Red Servi',
                'status' => 'active',
                'secure_thumbnail' => 'https://multimedia.epayco.co/plugins-sdks/Red%20servi.png'
            ]
        ];


        $payment_list = [
            'type'                 => 'mp_checkbox_list',
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
     * Get Epayco Icon
     *
     * @param bool $adminVersion
     *
     * @return string
     */
    private function getCheckoutIcon(bool $adminVersion = false): string
    {
        $iconName = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/efectivo.png';
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
                'id' => 'sured',
                'name' => 'Su Red',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Sured.png')
            ],
            [
                'id' => 'efecty',
                'name' => 'Efecty',
                'status' => 'active',
                'thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Efecty.png')
            ],
            [
                'id' => 'gana',
                'name' => 'Gana',
                'status' => 'active',
                'thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('gana.png')
            ],
            [
                'id' => 'puntored',
                'name' => 'Punto Red',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Puntored.png')
            ],
            [
                'id' => 'redservi',
                'name' => 'Red Servi',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Redservi.png')
            ],
            [
                'id' => 'redservicioscesar',
                'name' => 'Red Servicios del Cesar',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Reddeservicios.png')
            ],
            [
                'id' => 'apuestascucuta',
                'name' => 'Apuestas Cúcuta 75',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Apuestascucuta.png')
            ],
            [
                'id' => 'suchance',
                'name' => 'SuChance',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('SuChance.png')
            ],
            [
                'id' => 'laperla',
                'name' => 'La Perla',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Laperla.png')
            ],
            [
                'id' => 'jer',
                'name' => 'Jer',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Jer.png')
            ],
            [
                'id' => 'pagatodo',
                'name' => 'Paga Todo',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Pagatodo.png')
            ],
            [
                'id' => 'acertemos',
                'name' => 'Acertemos',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Acertemos.png')
            ],
            [
                'id' => 'ganagana',
                'name' => 'Gana Gana',
                'status' => 'active',
                'secure_thumbnail' => $this->epayco->hooks->gateway->getGatewayIcon('Ganagana.png')
            ],
        ];

        $order = [
            'Su Red',
            'Paga Todo',
            'Gana',
            'Acertemos',
            'Gana Gana',
            'SuChance',
            'Red Servicios del Cesar',
            'Apuestas Cúcuta 75',
            'Jer',
            'La Perla',
            'Efecty',
            'Punto Red',
            'Red Servi',
        ];

        $activePaymentMethods = [];
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

        // Ordenar según el array $order
        usort($activePaymentMethods, function ($a, $b) use ($order) {
            $posA = array_search($a['name'], $order);
            $posB = array_search($b['name'], $order);
            return $posA - $posB;
        });

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
        // $transactionDetails  =  $this->epayco->orderMetadata->getTicketTransactionDetailsMeta($order);
        $transactionDetails  =  $this->epayco->orderMetadata->getPaymentsIdMeta($order);
        if (empty($transactionDetails)) {
            return;
        }

        /*$this->epayco->hooks->template->getWoocommerceTemplate(
            'public/order/ticket-order-received.php',
            [
                'print_ticket_label'  => '',
                'transaction_details' => $transactionDetails,
            ]
        );*/

        $lastPaymentId  =  $this->epayco->orderMetadata->getPaymentsIdMeta($order);
        $paymentInfo = json_decode(wp_json_encode($lastPaymentId), true);

        if (empty($paymentInfo)) {
            return;
        }

        $paymentsIdArray = explode(', ', $paymentInfo);

        $data = array(
            "filter" => array("referencePayco" => $paymentsIdArray[0]),
            "success" => true
        );
        $this->transaction = new TicketTransaction($this, $order, []);
        //$transactionDetails = $this->transaction->sdk->transaction->get($paymentsIdArray[0]);
        $transactionDetails = $this->transaction->sdk->transaction->get($data, true, "POST");
        // $sdk = $this->sdk->transaction->get($data);
        $transactionInfo = json_decode(wp_json_encode($transactionDetails), true);

        if (empty($transactionInfo)) {
            return;
        }

        $transaction = $this->transaction->returnParameterToThankyouPage($transactionInfo, $this, $order_id);

        if (empty($transaction)) {
            return;
        }
        $transaction['pin'] = $paymentsIdArray[1];
        $transaction['codeProject'] = $paymentsIdArray[2];
        $transaction['ticket_header'] = $this->storeTranslations['ticket_header'];
        $transaction['ticket_footer'] = $this->storeTranslations['ticket_footer'];
        $transaction['expirationDate'] = $paymentsIdArray[3];
        $transaction['expirationDateText'] = $this->storeTranslations['expirationDate'];

        $this->epayco->hooks->template->getWoocommerceTemplate(
            'public/order/order-received.php',
            $transaction
        );
    }
}
