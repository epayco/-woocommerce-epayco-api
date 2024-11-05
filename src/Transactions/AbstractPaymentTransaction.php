<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Gateways\AbstractGateway;

abstract class AbstractPaymentTransaction extends AbstractTransaction
{
    /**
     * Payment Transaction constructor
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order, array $checkout)
    {
        parent::__construct($gateway, $order, $checkout);
        $this->transaction = $this->sdk->getPaymentInstance();
    }



    /**
     * Create Payment
     *
     * @return string|array
     * @throws \Exception
     */
    public function createTcPayment($order_id, array $checkout)
    {
        $order = new \WC_Order($order_id);
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
        //$customerData = $this->getCustomer($checkout);
        $basedCountry = $checkout["countryType"]??$checkout["countrytype"]??$checkout[""]["countryType"];
        $city = $checkout["country"]??$checkout[""]["country"];
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["response_url"];
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        $dues= $checkout["installmet"]??$checkout[""]["installmet"];
        //$person_type= $checkout["person_type"]??$checkout[""]["person_type"];
        $holder_address= $checkout["address"]??$checkout[""]["address"];
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"]??$checkout[""]["identificationType"];
        $doc_number= $checkout["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphone"]??$checkout[""]["cellphone"];
        /*if(!$customerData['success']){
            return $customerData;
        }*/
        $data = array(
            "token_card" => $checkout["token"],
            "customer_id" => "customer_id",
            "bill" => (string)$order->get_id(),
            "dues" => $dues,
            "description" => $descripcion,
            "value" =>(string)$order->get_total(),
            "tax" => $iva,
            "tax_base" => $base_tax,
            "currency" => $currency,
            "doc_type" => $doc_type,
            "doc_number" => $doc_number,
            "name" => $name,
            "last_name" => $lastName,
            "email" => $email,
            "country" => $basedCountry,
            "address"=> $holder_address,
            "city" => $city,
            "cell_phone" => $cellphone,
            "ip" => $myIp,
            "url_response" => $response_url,
            "url_confirmation" => $confirm_url,
            "metodoconfirmacion" => "POST",
            "use_default_card_customer" => true,
            "extra1" => (string)$order->get_id(),
            "extras_epayco"=>["extra5"=>"P19"]
        );
        $charge = $this->sdk->charge->create($data);
        return $charge;
    }

    /**
     * Create Payment
     *
     * @return string|array
     * @throws \Exception
     */
    public function createSubscriptionPayment($order_id, array $checkout)
    {
        $order = new \WC_Order($order_id);
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
        //$customerData = $this->getCustomer($checkout);
        $basedCountry = $checkout["countryType"]??$checkout["countrytype"]??$checkout[""]["countryType"];
        $city = $checkout["country"]??$checkout[""]["country"];
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["response_url"];
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        $dues= $checkout["installmet"]??$checkout[""]["installmet"];
        //$person_type= $checkout["person_type"]??$checkout[""]["person_type"];
        $holder_address= $checkout["address"]??$checkout[""]["address"];
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"]??$checkout[""]["identificationType"];
        $doc_number= $checkout["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphone"]??$checkout[""]["cellphone"];
        /*if(!$customerData['success']){
            return $customerData;
        }*/
        $data = array(
            "token_card" => $checkout["token"],
            "customer_id" => "customer_id",
            "bill" => (string)$order->get_id(),
            "dues" => $dues,
            "description" => $descripcion,
            "value" =>(string)$order->get_total(),
            "tax" => $iva,
            "tax_base" => $base_tax,
            "currency" => $currency,
            "doc_type" => $doc_type,
            "doc_number" => $doc_number,
            "name" => $name,
            "last_name" => $lastName,
            "email" => $email,
            "country" => $basedCountry,
            "address"=> $holder_address,
            "city" => $city,
            "cell_phone" => $cellphone,
            "ip" => $myIp,
            "url_response" => $response_url,
            "url_confirmation" => $confirm_url,
            "metodoconfirmacion" => "POST",
            "use_default_card_customer" => true,
            "extra1" => (string)$order->get_id(),
            "extras_epayco"=>["extra5"=>"P19"]
        );
        die();
         $charge = $this->sdk->charge->create($data);
        return $charge;
    }


    /**
     * Create Payment
     *
     * @return string|array
     * @throws \Exception
     */
    public function createPsePayment($order_id, array $checkout)
    {
        $order = new \WC_Order($order_id);
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
        //$basedCountry = WC()->countries->get_base_country();
        $basedCountry = $checkout["countryType"]??$checkout["countrytype"]??$checkout[""]["countryType"];
        $city = $checkout["country"]??$checkout[""]["country"];
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["response_url"];
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        $bank = $checkout["bank"]??$checkout[""]["bank"];
        $person_type= $checkout["person_type"]??$checkout[""]["person_type"];
        $holder_address= $checkout["address"]??$checkout[""]["address"];
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"]??$checkout[""]["identificationType"];
        $doc_number= $checkout["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphone"]??$checkout[""]["cellphone"];
        $data = array(
            "bank" => $bank,
            "invoice" => (string)$order->get_id(),
            "description" => $descripcion,
            "value" =>$order->get_total(),
            "tax" => $iva,
            "taxBase" => $base_tax,
            "currency" => $currency,
            "typePerson" => $person_type=='PN'?"0":"1",
            "address" => $holder_address,
            "docType" => $doc_type,
            "docNumber" => $doc_number,
            "name" =>$name,
            "lastName" => $lastName,
            "email" => $email,
            "country" => $basedCountry,
            "city" => $city,
            "cellPhone" => $cellphone,
            "ip" => $myIp,
            "urlResponse" => $response_url,
            "urlConfirmation" => $confirm_url,
            "methodConfirmation" => "POST",
            "extra1" => (string)$order->get_id(),
            "testMode" => $testMode,
            "extras_epayco"=>["extra5"=>"P19"]
        );
        $pse = $this->sdk->bank->create($data);
        return $pse;
    }

    /**
     * Create Payment
     *
     * @return string|array
     * @throws \Exception
     */
    public function createCashPayment($order_id, array $checkout)
    {
        $order = new \WC_Order($order_id);
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
        //$basedCountry = WC()->countries->get_base_country();
        $basedCountry = $checkout["countryType"]??$checkout["countrytype"];
        $city = $checkout["country"];
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["confirm_url"];
        $end_date = date('y-m-d', strtotime(sprintf('+%s days',$checkout["date_expiration"]) ));
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        $person_type= $checkout["person_type"];
        $holder_address= $checkout["address"];
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"];
        $doc_number= $checkout["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"];
        $cellphone= $checkout["cellphone"];
        $data = array(
            "paymentMethod" => $checkout["paymentMethod"],
            "invoice" => (string)$order->get_id(),
            "description" => $descripcion,
            "value" =>(string)$order->get_total(),
            "tax" => (string)$iva,
            "taxBase" => (string)$base_tax,
            "currency" => $currency,
            "type_person" => $person_type=='PN'?"0":"1",
            "address" => $holder_address,
            "docType" => $doc_type,
            "docNumber" => $doc_number,
            "name" => $name,
            "lastName" => $lastName,
            "email" => $email,
            "country" => $basedCountry,
            "city" => $city,
            "cellPhone" => $cellphone,
            "endDate" => $end_date,
            "ip" => $myIp,
            "urlResponse" => $response_url,
            "urlConfirmation" => $confirm_url,
            "methodConfirmation" => "POST",
            "extra1" => (string)$order->get_id(),
            "vtex" => true,
            "testMode" => $testMode,
            "extras_epayco"=>["extra5"=>"P19"]
        );
        $cash = $this->sdk->cash->create($data);

        $cash = json_decode(json_encode($cash), true);
        return $cash;
    }

    /**
     * Create Payment
     *
     * @return string|array
     * @throws \Exception
     */
    public function createDaviplataPayment($order_id, array $checkout)
    {
        $order = new \WC_Order($order_id);
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
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["response_url"];
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        $person_type= $checkout["person_type"]??$checkout[""]["person_type"];
        $holder_address= $checkout["address"]??$checkout[""]["address"];
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"];
        $doc_number= $checkout["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphone"]??$checkout[""]["cellphone"];
        $cellphonetype = $_POST["cellphoneType"]??$checkout["cellphonetype"]??$checkout[""]["cellphonetype"];
        $cellphonetypeIn = explode("+", $cellphonetype)[1];
        $city = WC()->countries->get_base_city() !='' ? WC()->countries->get_base_city():$order->get_shipping_city();
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $data = array(
            "invoice" => (string)$order->get_id(),
            "description" => $descripcion,
            "value" =>(string)$order->get_total(),
            "tax" => (string)$iva,
            "taxBase" => (string)$base_tax,
            "currency" => $currency,
            "type_person" => $person_type=='PN'?"0":"1",
            "address" => $holder_address,
            "docType" => $doc_type,
            "document" => $doc_number,
            "name" => $name,
            "lastName" => $lastName,
            "email" => $email,
            "country" => $basedCountry,
            "indCountry" => $cellphonetypeIn,
            "city" => $city,
            "phone" => $cellphone,
            "ip" => $myIp,
            "urlResponse" => $response_url,
            "urlConfirmation" => $confirm_url,
            "methodConfirmation" => "POST",
            "extra1" => (string)$order->get_id(),
            "vtex" => true,
            "testMode" => $testMode,
            "extras_epayco"=>["extra5"=>"P19"]
        );
        $daviplata = $this->sdk->daviplata->create($data);
        $daviplata= json_decode(json_encode($daviplata), true);
        return $daviplata;
    }

    public function getCustomer($customerData)
    {
        global $wpdb;
        $table_name_customer = $wpdb->prefix . 'epayco_customer';
        $customerGetData = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name_customer WHERE email = %s",
                $customerData['email']
            )
        );
        if (count($customerGetData) == 0) {
           $customer = $this->customerCreate($customerData);
            if (is_array($customer) && $customer['success']) {
                $inserCustomer = $wpdb->insert(
                    $table_name_customer,
                    [
                        'customer_id' => $customer['data']['customerId'],
                        'token_id' => $customerData['token'],
                        'email' => $customerData['email']
                    ]
                );
                if (!$inserCustomer) {
                    $response_status = [
                        'success' => false,
                        'message' => 'internar error, tray again'
                    ];
                    return $response_status;
                }{
                    $response_status = [
                        'success' => true,
                        'customer_id' => $customer['data']['customerId']
                    ];
                    return $response_status;
                }
            }else{
                $response_status = [
                    'success' => false,
                    'message' => $customer['message']
                ];
                return $response_status;
            }
        }else{
            $count_customers = 0;
            for ($i = 0; $i < count($customerGetData); $i++) {
                if ($customerGetData[$i]->email == $customerData['email']) {
                    $count_customers += 1;
                }
            }
            if ($count_customers == 0) {
                $customer = $this->customerCreate($customerData);
                if (is_array($customer) && !$customer['success']) {
                    $response_status = [
                        'success' => false,
                        'message' => $customer['message']
                    ];
                    return $response_status;
                }
                $inserCustomer = $wpdb->insert(
                    $table_name_customer,
                    [
                        'customer_id' => $customer['data']['customerId'],
                        'token_id' => $customerData['token'],
                        'email' => $customerData['email']
                    ]
                );
                if (!$inserCustomer) {
                    $response_status = [
                        'success' => false,
                        'message' => 'internar error, tray again'
                    ];
                    return $response_status;
                }
                $response_status = [
                    'success' => true,
                    'customer_id' => $customer['data']['customerId']
                ];
                return $response_status;
            } else {
                for ($i = 0; $i < count($customerGetData); $i++) {
                    if ($customerGetData[$i]->email == $customerData['email'] && $customerGetData[$i]->token_id != $customerData['token']) {
                        $this->customerAddToken($customerGetData[$i]->customer_id, $customerData['token']);
                    }
                    $customerData['customer_id'] = $customerGetData[$i]->customer_id;
                }
                $response_status = [
                    'success' => true,
                    'customer_id' => $customerData['customer_id']
                ];
                return $response_status;
            }
        }
    }

    public function customerCreate(array $data)
    {
        $customer = false;
        try {
            $customer = $this->sdk->customer->create(
                [
                    "token_card" => $data['token'],
                    "name" => $data['name'],
                    "email" => $data['email'],
                    "phone" => $data['cellphone'],
                    "cell_phone" => $data['cellphone'],
                    "country" => $data['country'],
                    "address" => $data['address'],
                    "default" => true
                ]
            );
            $customer = json_decode(json_encode($customer), true);
        } catch (Exception $exception) {
            echo 'create client: ' . $exception->getMessage();
            die();
        }

        return $customer;
    }

    public function customerAddToken($customer_id, $token_card)
    {
        $customer = false;
        try {
            $customer = $this->sdk->customer->addNewToken(
                [
                    "token_card" => $token_card,
                    "customer_id" => $customer_id
                ]
            );
        } catch (Exception $exception) {
            echo 'add token: ' . $exception->getMessage();
            die();
        }

        return $customer;
    }


    public function string_sanitize($string, $force_lowercase = true, $anal = false) {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]","}", "\\", "|", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;","â€”", "â€“", "<", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "_", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
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
}
