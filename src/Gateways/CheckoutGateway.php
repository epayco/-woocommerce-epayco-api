<?php

namespace Epayco\Woocommerce\Gateways;

use Exception;
use Epayco as EpaycoSdk;
if (!defined('ABSPATH')) {
    exit;
}

class CheckoutGateway extends AbstractGateway
{
    /**
     * ID
     *
     * @const
     */
    public const ID = 'woo-epayco-checkout';

    /**
     * @const
     */
    public const CHECKOUT_NAME = 'checkout-epayco';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = 'WC_Epayco_Checkout_Gateway';

    /**
     * @const
     */

    /**
     * TicketGateway constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->checkoutGatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->epaycoCheckout;
        $this->id        = self::ID;
        $this->icon      = $this->epayco->hooks->gateway->getGatewayIcon('icon-checkout.png');
        $this->iconAdmin = $this->epayco->hooks->gateway->getGatewayIcon('icon-checkout.png');
        $this->title     = $this->epayco->storeConfig->getGatewayTitle($this, $this->adminTranslations['gateway_title']);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['method_title'];
        $this->method_description = $this->description;

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderThankYouPage']);
        $this->epayco->hooks->gateway->registerGatewayReceiptPage($this->id, [$this, 'receiptPage']);
        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_API_NAME, [$this, 'webhook']);
        $lang = get_locale();
        $lang = explode('_', $lang);
        $lang = $lang[0];
        $this->epaycoSdk = new EpaycoSdk\Epayco(
            [
                "apiKey" => $this->get_option('apiKey'),
                "privateKey" => $this->get_option('privateKey'),
                "lenguage" => strtoupper($lang),
                "test" => (bool)$this->get_option('environment')
            ]
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
                    'icon'        => 'ep-icon-badge-info',
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
            'title' => [
                'type'        => 'text',
                'title'       => $this->adminTranslations['title_title'],
                'description' => $this->adminTranslations['title_description'],
                'default'     => $this->adminTranslations['title_default'],
                'desc_tip'    => $this->adminTranslations['title_desc_tip'],
                'class'       => 'limit-title-max-length',
            ],
            'epayco_type_checkout' => [
                'type'         => 'mp_toggle_switch',
                'title'        => $this->adminTranslations['epayco_type_checkout_title'],
                'subtitle'     => $this->adminTranslations['epayco_type_checkout_subtitle'],
                'default'      => 'no',
                'descriptions' => [
                    'enabled'  => $this->adminTranslations['epayco_type_checkout_descriptions_enabled'],
                    'disabled' => $this->adminTranslations['epayco_type_checkout_descriptions_disabled'],
                ],
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
            'wc_epayco_checkout',
            'https://epayco-checkout-testing.s3.amazonaws.com/checkout.preprod.js'
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
            'public/checkout/epayco-checkout.php',
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
            'terms_and_conditions_label'       => $this->storeTranslations['terms_and_conditions_label'],
            'terms_and_conditions_description' => $this->storeTranslations['terms_and_conditions_description'],
            'terms_and_conditions_link_text'   => $this->storeTranslations['terms_and_conditions_link_text'],
            'and_the'   => $this->storeTranslations['and_the'],
            'terms_and_conditions_link_src'    => 'https://epayco.com/terminos-y-condiciones-usuario-pagador-comprador/',
            'personal_data_processing_link_text'    => $this->storeTranslations['personal_data_processing_link_text'],
            'personal_data_processing_link_src'    => 'https://epayco.com/tratamiento-de-datos/',
            'site_id'                          => 'epayco',
            'logo' =>       $this->epayco->hooks->gateway->getGatewayIcon('logo.png'),
            'icon_info' =>       $this->epayco->hooks->gateway->getGatewayIcon('icon-info.png'),
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
            $urlReceived =  $order->get_checkout_payment_url( true );
            return [
                'result'   => 'success',
                'redirect' => $urlReceived,
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
     * Generate the epayco form
     *
     * @param mixed $order_id
     * @return string
     */
    public function generate_epayco_form( $order_id )
    {
        global $woocommerce;
        $order = wc_get_order($order_id);
        $descripcionParts = array();
        $iva=0;
        $ico=0;
        $base_tax=$order->get_subtotal()-$order->get_total_discount();
        foreach($order->get_items('tax') as $item_id => $item ) {
            if( strtolower( $item->get_label() ) == 'iva' ){
                $iva += round($item->get_tax_total(),2);
            }
            if( strtolower( $item->get_label() ) == 'ico'){
                $ico += round($item->get_tax_total(),2);
            }
        }

        foreach ($order->get_items() as $product) {
            $clearData = str_replace('_', ' ', $this->string_sanitize($product['name']));
            $descripcionParts[] = $clearData;
        }

        $descripcion = implode(' - ', $descripcionParts);
        $currency = strtolower(get_woocommerce_currency());
        $basedCountry = WC()->countries->get_base_country();
        $external=$this->get_option('epayco_type_checkout') != 'yes' ? 'true':'false';
        $redirect_url =get_site_url() . "/";
        $redirect_url = add_query_arg( 'wc-api', self::WEBHOOK_API_NAME, $redirect_url );
        $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
        $confirm_url = $redirect_url.'&confirmation=1';
        $redirect_url = $order->get_checkout_order_received_url();

        $myIp= $this->getCustomerIp();
        $lang = substr(get_locale(), 0, 2);

        $name_billing=$order->get_billing_first_name().' '.$order->get_billing_last_name();
        $address_billing=$order->get_billing_address_1();
        $phone_billing=@$order->billing_phone;
        $email_billing=@$order->billing_email;
        $orderStatus = "pending";
        $current_state = $order->get_status();
        if($current_state != $orderStatus){
            $order->update_status($orderStatus);
        }
        //$order->update_status("on-hold");
        $this->epayco->woocommerce->cart->empty_cart();
        $public_key = $this->epayco->sellerConfig->getCredentialsPublicKeyPayment();
        $private_key = $this->epayco->sellerConfig->getCredentialsPrivateKeyPayment();
        $testMode = $this->epayco->storeConfig->isTestMode() ? "true" : "false";
        echo sprintf('
                    <script> var handler = ePayco.checkout.configure({
                        key: "%s",
                        test: "%s"
                    })
                    var date = new Date().getTime();
                    var bntPagar = document.getElementById("btn_epayco");
                    var data = {
                        name: "%s",
                        description: "%s",
                        invoice: "%s",
                        currency: "%s",
                        amount: "%s".toString(),
                        tax_base: "%s".toString(),
                        tax: "%s".toString(),
                        taxIco: "%s".toString(),
                        country: "%s",
                        lang: "%s",
                        external: "%s",
                        confirmation: "%s",
                        response: "%s",
                        name_billing: "%s",
                        address_billing: "%s",
                        email_billing: "%s",
                        mobilephone_billing: "%s",
                        autoclick: "true",
                        ip: "%s",
                        test: "%s".toString(),
                        extra1: "%s",
                        extras_epayco:{extra5:"p19"},
                        method_confirmation: "POST"
                    }
                    const apiKey = "%s";
                    const privateKey = "%s";
                    var openNewChekout = function () {
                        if(localStorage.getItem("invoicePayment") == null){
                            localStorage.setItem("invoicePayment", data.invoice);
                            makePayment(privateKey,apiKey,data, data.external == "true"?true:false)
                        }else{
                            if(localStorage.getItem("invoicePayment") != data.invoice){
                                localStorage.removeItem("invoicePayment");
                                localStorage.setItem("invoicePayment", data.invoice);
                                makePayment(privateKey,apiKey,data, data.external == "true"?true:false)
                            }else{
                                makePayment(privateKey,apiKey,data, data.external == "true"?true:false)
                            }
                        }
                    }
                    var makePayment = function (privatekey, apikey, info, external) {
                        const headers = { "Content-Type": "application/json" } ;
                        headers["privatekey"] = privatekey;
                        headers["apikey"] = apikey;
                        var payment =   function (){
                            return  fetch("https://cms.epayco.io/checkout/payment/session", {
                                method: "POST",
                                body: JSON.stringify(info),
                                headers
                            })
                                .then(res =>  res.json())
                                .catch(err => err);
                        }
                        payment()
                            .then(session => {
                                bntPagar.style.pointerEvents = "all";
                                if(session.data.sessionId != undefined){
                                    localStorage.removeItem("sessionPayment");
                                    localStorage.setItem("sessionPayment", session.data.sessionId);
                                    const handlerNew = window.ePayco.checkout.configure({
                                        sessionId: session.data.sessionId,
                                        external: external,
                                    });
                                    handlerNew.openNew()
                                }else{
                                    handler.open(data)
                                }
                            })
                            .catch(error => {
                                error.message;
                            });
                    }
                    var openChekout = function () {
                        bntPagar.style.pointerEvents = "none";
                        openNewChekout()
                    }
                    bntPagar.addEventListener("click", openChekout);
            	    openChekout()
                </script>
                </form>
                </center>
        ',  trim($public_key),
            $testMode,
            $descripcion,
            $descripcion,
            $order->get_id()."_wc_".$order->get_id(),
            $currency,
            $order->get_total(),
            $base_tax,
            $iva,
            $ico,
            $basedCountry,
            $lang,
            $external,
            $confirm_url,
            $redirect_url,
            $name_billing,
            $address_billing,
            $email_billing,
            $phone_billing,
            $myIp,
            $testMode,
            $order->get_id(),
            trim($public_key),
            trim($private_key)
        );
        wp_enqueue_script('epayco',  'https://epayco-checkout-testing.s3.amazonaws.com/checkout.preprod.js', array(), '1.0.0', null);
        return '<form  method="post" id="appGateway">
		        </form>';
    }

    /**
     * Render Receipt  page
     *
     * @param $order_id
     */
    public function receiptPage($order_id): void
    {
        echo ' <div class="loader-container">
                    <div class="loading"></div>
                </div>
                <p style="text-align: center;" class="epayco-title">
                    <span class="animated-points">Cargando métodos de pago</span>
                    <br><small class="epayco-subtitle"></small>
                </p>';
        $idioma = substr(get_locale(), 0, 2);
        if ($idioma === "en") {
            $epaycoButtonImage = 'https://multimedia.epayco.co/epayco-landing/btns/Boton-epayco-color-Ingles.png';
        }else{
            $epaycoButtonImage = 'https://multimedia.epayco.co/epayco-landing/btns/Boton-epayco-color1.png';
        }
        echo '<p>       
                 <center>
                    <a id="btn_epayco" href="#">
                       <img src="'.$epaycoButtonImage.'">
                    </a>
                 </center> 
               </p>';

        echo $this->generate_epayco_form( $order_id );
    }


    /**
     *
     * @string $string
     */
    public function string_sanitize($string):string
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]","}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;","â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "_", $clean);
        return $clean;
    }

    public function getCustomerIp(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Render thank you page
     *
     * @param $order_id
     */
    public function renderThankYouPage($order_id): void
    {
        $order        = wc_get_order($order_id);
        $ref_payco = sanitize_text_field($_REQUEST['ref_payco']);
        if(empty($ref_payco)){
            $order_id_explode = explode('=',$ref_payco);
            $order_id_rpl  = str_replace('?ref_payco','',$order_id_explode);
            $ref_payco =$order_id_rpl[0];
        }
        if (!$ref_payco)
        {
            $explode=explode('=',$order_id);
            $ref_payco=$explode[1];
        }

        if($ref_payco){
            $url = 'https://secure.epayco.io/validation/v1/reference/'.$ref_payco;
            $response = wp_remote_get(  $url );
            $body = wp_remote_retrieve_body( $response );
            $jsonData = @json_decode($body, true);
            $validationData = $jsonData['data'];
            $status = trim($validationData['x_response']);
            $alert_title = trim($validationData['x_response']);
            $ref_payco =trim($validationData['x_ref_payco']);
            $test = trim($validationData['x_test_request']) == 'TRUE' ? 'Pruebas' : 'Producción';
            $transactionDateTime=trim($validationData['x_transaction_date']);
            $bank= trim($validationData['x_franchise']);
            $authorization= trim($validationData['x_approval_code']);
            $factura = trim($validationData['x_id_factura']);
            $descripcion = trim($validationData['x_description']);
            $valor = trim($validationData['x_amount']);
            $iva = trim($validationData['x_tax']);
            $estado = trim($validationData['x_respuesta']);
            $currency = trim($validationData['x_currency_code']);
            $name =  trim($validationData['x_customer_name'])." ".trim($validationData['x_customer_lastname']);
            $card = trim($validationData['x_franchise']);
            $ip = trim($validationData['x_customer_ip']);
            switch ($status) {
                case 'Aceptada': {
                    $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('check.png');
                    $iconColor = '#67C940';
                    $message = $this->storeTranslations['success_message'];
                }break;
                case 'Pendiente':
                case 'Pending':{
                    $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('warning.png');
                    $iconColor = '#FFD100';
                    $message = $this->storeTranslations['pending_message'];
                }break;
                default: {
                    $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('error.png');
                    $iconColor = '#E1251B';
                    $message = $this->storeTranslations['fail_message'];
                }break;
            }
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
                'epayco_icon' => $this->epayco->hooks->gateway->getGatewayIcon('logo_white.png'),
                'ip' => $ip,
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
            $this->epayco->orderMetadata->updatePaymentsOrderMetadata($order,[$ref_payco]);
            $this->epayco->hooks->template->getWoocommerceTemplate(
                'public/order/order-received.php',
                $transaction
            );
        }


    }

}