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
    public const WEBHOOK_DONWLOAD = 'Donwload';

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
        $this->icon      = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/checkout.png';
        $this->iconAdmin = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/checkout.png';
        $this->title = $this->epayco->storeConfig->getGatewayTitle($this, 'Checkout ePayco');

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['method_title'];
        $this->method_description = $this->description;

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderThankYouPage']);
        $this->epayco->hooks->gateway->registerGatewayReceiptPage($this->id, [$this, 'receiptPage']);
        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_DONWLOAD, [$this, 'validate_epayco_request']);
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
                'default'      => 'default',
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
            'epayco_type_checkout' => [
                'type'         => 'mp_toggle_switch',
                'title'        => $this->adminTranslations['epayco_type_checkout_title'],
                'subtitle'     => $this->adminTranslations['epayco_type_checkout_subtitle'],
                'default'      => 'yes',
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
            'https://epayco-checkout-testing.s3.us-east-1.amazonaws.com/checkout.preprod_v1.js'
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
            'terms_and_conditions_link_src'    => 'https://epayco.com/terminos-y-condiciones-generales/',
            'personal_data_processing_link_text'    => $this->storeTranslations['personal_data_processing_link_text'],
            'personal_data_processing_link_src'    => 'https://epayco.com/tratamiento-de-datos/',
            'site_id'                          => 'epayco',
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
            $tax_label = trim(strtolower($item->get_label()));

            if ($tax_label == 'iva') {
                $iva += round($item->get_tax_total(), 2);
            }

            if ($tax_label == 'ico') {
                $ico += round($item->get_tax_total(), 2);
            }
        }
        $iva = $iva !== 0 ? $iva : $order->get_total() - $base_tax;

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
        echo  sprintf('
                    <script> var handler = ePayco.checkout.configure({
                        key: "%1$s",
                        test: "%2$s"
                    })
                    var date = new Date().getTime();
                    var bntPagar = document.getElementById("btn_epayco");
                    var data = {
                        name: "%3$s",
                        description: "%4$s",
                        invoice: "%5$s",
                        currency: "%6$s",
                        amount: "%7$s".toString(),
                        tax_base: "%8$s".toString(),
                        tax: "%9$s".toString(),
                        taxIco: "%10$s".toString(),
                        country: "%11$s",
                        lang: "%12$s",
                        external: "%13$s",
                        confirmation: "%14$s",
                        response: "%15$s",
                        name_billing: "%16$s",
                        address_billing: "%17$s",
                        email_billing: "%18$s",
                        mobilephone_billing: "%19$s",
                        autoclick: "true",
                        ip: "%20$s",
                        test: "%21$s".toString(),
                        extra1: "%22$s",
                        extras_epayco:{extra5:"p19"},
                        method_confirmation: "POST"
                    }
                    const apiKey = "%23$s";
                    const privateKey = "%24$s";
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
                            return  fetch("https://cms.epayco.co/checkout/payment/session", {
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
        ',  esc_js( trim( $public_key ) ),           // 1
            esc_js( $testMode ),                         // 2
            esc_js( $descripcion ),                  // 3
            esc_js( $descripcion ),                  // 4
            esc_js( $order->get_id() ),              // 5
            esc_js( $currency ),                     // 6
            esc_js( $order->get_total() ),           // 7
            esc_js( $base_tax ),                     // 8
            esc_js( $iva ),                          // 9
            esc_js( $ico ),                          //10
            esc_js( $basedCountry ),                 //11
            esc_js( $lang ),                         //12
            $external === 'true' ? 'true' : 'false', //13 (sin comillas para boolean JS)
            esc_url_raw( $confirm_url ),             //14
            esc_url_raw( $redirect_url ),            //15
            esc_js( $name_billing ),                 //16
            esc_js( $address_billing ),              //17
            esc_js( $email_billing ),                //18
            esc_js( $phone_billing ),                //19
            esc_js( $myIp ),                         //20
            esc_js( $testMode ),                        //21
            esc_js( $order->get_id() ),              //22
            esc_js( trim( $public_key ) ),           //23
            esc_js( trim( $private_key ) )           //24
        );
        wp_enqueue_script('epayco',  'https://epayco-checkout-testing.s3.us-east-1.amazonaws.com/checkout.preprod_v1.js', array(), '1.0.0', null);
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
                       <img src="'. esc_url( $epaycoButtonImage ) .'">
                    </a>
                 </center> 
               </p>';

        echo wp_kses_post( $this->generate_epayco_form( $order_id ) );
    }


    /**
     *
     * @string $string
     */
    public function string_sanitize($string):string
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]","}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;","â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", wp_strip_all_tags($string)));
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
            $url = 'https://api.secure.payco.co/validation/v1/reference/'.$ref_payco;
            $response = wp_remote_get(  $url );
            $body = wp_remote_retrieve_body( $response );
            $jsonData = @json_decode($body, true);
            $validationData = $jsonData['data'];
            $x_amount = trim($validationData['x_amount']);
            $x_amount_base = trim($validationData['x_amount_base']);
            $x_cardnumber = trim($validationData['x_cardnumber']);
            $x_id_invoice = trim($validationData['x_id_invoice']);
            $x_franchise = trim($validationData['x_franchise']);
            $x_transaction_id = trim($validationData['x_transaction_id']);
            $x_transaction_date = trim($validationData['x_transaction_date']);
            $x_transaction_state = trim($validationData['x_transaction_state']);
            $x_customer_ip = trim($validationData['x_customer_ip']);
            $x_description = trim($validationData['x_description']);
            $x_response= trim($validationData['x_response']);
            $x_response_reason_text= trim($validationData['x_response_reason_text']);
            $x_approval_code= trim($validationData['x_approval_code']);
            $x_ref_payco= trim($validationData['x_ref_payco']);
            $x_tax= trim($validationData['x_tax']);
            $x_currency_code= trim($validationData['x_currency_code']);
            switch ($x_response) {
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
                    global $woocommerce;
                    $woocommerce->cart->empty_cart();
                    foreach ($order->get_items() as $item) {
                        // Get an instance of corresponding the WC_Product object
                        $product_id = $item->get_product()->id;
                        $product = $item->get_product();
                        $qty = $item->get_quantity(); // Get the item quantity
                        // Verificar si el producto es una variación
                        if ($product->is_type('variation')) {
                            WC()->cart->add_to_cart($product_id, $qty, $product->get_id(), $product->get_attributes());
                        }else{
                            WC()->cart->add_to_cart($product_id, (int)$qty);
                        }
                    }
                    wp_safe_redirect(wc_get_checkout_url());
                    exit();
                }break;
            }
            $donwload_url =get_site_url() . "/";
            $donwload_url = add_query_arg( 'wc-api', self::WEBHOOK_DONWLOAD, $donwload_url );
            $donwload_url = add_query_arg( 'refPayco', $x_ref_payco, $donwload_url );
            $donwload_url = add_query_arg( 'fecha', $x_transaction_date, $donwload_url );
            $donwload_url = add_query_arg( 'franquicia', $x_franchise, $donwload_url );
            $donwload_url = add_query_arg( 'descuento', '0', $donwload_url );
            $donwload_url = add_query_arg( 'autorizacion', $x_approval_code, $donwload_url );
            $donwload_url = add_query_arg( 'valor', $x_amount, $donwload_url );
            $donwload_url = add_query_arg( 'estado', $x_response, $donwload_url );
            $donwload_url = add_query_arg( 'descripcion', $x_description, $donwload_url );
            $donwload_url = add_query_arg( 'respuesta', $x_response, $donwload_url );
            $donwload_url = add_query_arg( 'ip', $x_customer_ip, $donwload_url );
            $is_cash = false;
            if($x_franchise == 'EF'||
                $x_franchise == 'GA'||
                $x_franchise == 'PR'||
                $x_franchise == 'RS'||
                $x_franchise == 'SR'
            ){
                $x_cardnumber_ = null;
            }else{
                if($x_franchise == 'PSE'){
                    $x_cardnumber_ = null;
                }else{
                    $x_cardnumber_ = isset($x_cardnumber)?substr($x_cardnumber, -8):null;
                }

            }
            $transaction = [
                'franchise_logo' => 'https://eks-checkout-service.epayco.io/img/methods/'.$x_franchise.'.svg',
                'x_amount_base' => $x_amount_base,
                'x_cardnumber' => $x_cardnumber_,
                'status' => $x_response,
                'type' => "",
                'refPayco' => $x_ref_payco,
                'factura' => $x_id_invoice,
                'descripcion_order' => $x_description,
                'valor' => $x_amount,
                'iva' => $x_tax,
                'estado' => $x_transaction_state,
                'response_reason_text' => $x_response_reason_text,
                'respuesta' => $x_response,
                'fecha' => $x_transaction_date,
                'currency' => $x_currency_code,
                'name' => '',
                'card' => '',
                'message' => $message,
                'error_message' => $this->storeTranslations['error_message'],
                'error_description' => $this->storeTranslations['error_description'],
                'payment_method'  => $this->storeTranslations['payment_method'],
                'response'=> $this->storeTranslations['response'],
                'dateandtime' => $this->storeTranslations['dateandtime'],
                'authorization' => $x_approval_code,
                'iconUrl' => $iconUrl,
                'iconColor' => $iconColor,
                'epayco_icon' => $this->epayco->hooks->gateway->getGatewayIcon('logo_white.png'),
                'ip' => $x_customer_ip,
                'totalValue' => $this->storeTranslations['totalValue'],
                'description' => $this->storeTranslations['description'],
                'reference' => $this->storeTranslations['reference'],
                'purchase' => $this->storeTranslations['purchase'],
                'iPaddress' => $this->storeTranslations['iPaddress'],
                'receipt' => $this->storeTranslations['receipt'],
                'authorizations' => $this->storeTranslations['authorization'],
                'paymentMethod'  => $this->storeTranslations['paymentMethod'],
                'epayco_refecence'  => $this->storeTranslations['epayco_refecence'],
                'donwload_url' => $donwload_url,
                'donwload_text' => $this->storeTranslations['donwload_text'],
                'code' => $this->storeTranslations['code']??null,
                'is_cash' => $is_cash
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