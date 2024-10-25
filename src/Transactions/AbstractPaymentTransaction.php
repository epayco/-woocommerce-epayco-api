<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Gateways\AbstractGateway;
use Epayco\Woocommerce\Helpers\Numbers;

abstract class AbstractPaymentTransaction extends AbstractTransaction
{
    /**
     * Payment Transaction constructor
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order, array $checkout)
    {
        parent::__construct($gateway, $order, $checkout);

        $this->transaction = $this->sdk->getPaymentInstance();

        /*$this->setCommonTransaction();
        $this->setPayerTransaction();
        $this->setAdditionalInfoTransaction();

        $this->transaction->description        = implode(', ', $this->listOfItems);
        $this->transaction->transaction_amount = Numbers::format($this->orderTotal);*/
    }

    /**
     * Create Payment
     *
     * @return string|array
     * @throws \Exception
     */
    public function createPayment()
    {
        $payment = $this->getTransaction('Payment');
        $payment->__set('session_id', $this->checkout['session_id']);
        $data = $payment->save();
        $this->epayco->logs->file->info('Payment created', $this->gateway::LOG_SOURCE, $data);
        return $data;
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
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $checkout["country"] = $basedCountry;
        $customerData = $this->getCustomer($checkout);
        if(!$customerData['success']){
            return $customerData;
        }
        $data = array(
            "token_card" => $checkout["token"],
            "customer_id" => $customerData['customer_id'],
            "bill" => (string)$order->get_id(),
            "dues" => $checkout["dues"],
            "description" => $descripcion,
            "value" =>(string)$order->get_total(),
            "tax" => $iva,
            "tax_base" => $base_tax,
            "currency" => $currency,
            "doc_type" => $checkout["documenttype"],
            "doc_number" => $checkout["doc_number"],
            "name" => $checkout["name"],
            "last_name" => $checkout["name"],
            "email" => $checkout["email"],
            "country" => $basedCountry,
            "address"=> $checkout["address"],
            "city" => "",
            "cell_phone" => $checkout["cellphone"],
            "ip" => $myIp,
            "url_response" => $confirm_url,
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
        $basedCountry = WC()->countries->get_base_country();
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
        $doc_type= $checkout["doc_type"]??$checkout[""]["doc_type"];
        $doc_number= $checkout["doc_number"]??$checkout[""]["doc_number"];
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
        //$this->epayco->logs->file->info('Payment created', $this->gateway::LOG_SOURCE, $data);
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
        $basedCountry = WC()->countries->get_base_country();
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["confirm_url"];
        $end_date = date('y-m-d', strtotime(sprintf('+%s days',$checkout["date_expiration"]) ));
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        $person_type= $checkout["person_type"]??$checkout[""]["person_type"];
        $holder_address= $checkout["address"]??$checkout[""]["address"];
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"];
        $doc_number= $checkout["doc_number"]??$checkout[""]["number"];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphone"]??$checkout[""]["cellphone"];
        $data = array(
            "paymentMethod" => $checkout["paymentMethod"],
            "invoice" => (string)$order->get_id()."_test_1",
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
        //$cash = '{"refPayco":101638264,"invoice":"45_test","description":"camiseta","value":25000,"tax":0,"ico":0,"taxBase":25000,"total":25000,"currency":"COP","bank":"EFECTY","status":"Pendiente","response":"Esperando pago del cliente en punto de servicio Efecty","autorization":"000000","receipt":"48771830820612","date":"2024-10-17 11:25:48","franchise":"EF","codResponse":3,"codError":"P004","ip":"192.168.32.1","testMode":1,"docType":"CC","document":"12145661231","name":"Ricardo","lastName":"Saldarriaga","email":"ric.salda.94+223@gmail.com","city":"","address":"NA","indCountry":null,"pin":"Prueba-000000","codeProject":110571,"paymentDate":"2024-10-17 11:25:48","expirationDate":"2024-10-20 23:59:59","conversionFactor":4266.2,"pesos":25000,"extras":{"extra1":"45","extra2":"","extra3":"","extra4":"","extra5":"","extra6":"","extra7":"","extra8":"","extra9":"","extra10":""},"extras_epayco":{"extra5":"P37"},"showConversion":1,"token":"eyJwaW4iOiJQcnVlYmEtMDAwMDAwIiwibmFtZXMiOiJSaWNhcmRvIFNhbGRhcnJpYWdhIiwiZGF0ZUV4cGlyYXRpb24iOiIyMDI0LTEwLTIwIDIzOjU5OjU5IiwidHlwZSI6IkVGIiwiY29kUHJvamVjdCI6MTEwNTcxLCJkYXRlIjoiMjAyNC0xMC0xNyAxMToyNTo0OCIsInRybSI6NDI2Ni4yLCJjdXJyZW5jeSI6IkNPUCIsInN1YlRvdGFsIjoyNTAwMCwidGF4IjowLCJpY28iOjAsImFtb3VudCI6MjUwMDAsImNvbXBhbnlOYW1lIjoiWXZvbm5lIEVzY2Fsb25hIiwid2ViIjoiaHR0cHM6XC9cL2VwYXljby5jb20iLCJzaG93Q29udmVyc2lvbiI6MX0="}';
        //$cash = json_decode($cash, true);

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
        $doc_type= $checkout["doc_type"]??$checkout[""]["doc_type"];
        $doc_number= $checkout["doc_number"]??$checkout[""]["number"];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphone"]??$checkout[""]["cellphone"];
        $cellphonetype = $checkout["cellphonetype"]??$checkout[""]["cellphonetype"];
        $city = WC()->countries->get_base_city() !='' ? WC()->countries->get_base_city():$order->get_shipping_city();
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $data = array(
            "invoice" => (string)$order->get_id()."_test_1",
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
            "indCountry" => $cellphonetype,
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
        //$daviplata = '{"refPayco":101638264,"invoice":"45_test","description":"camiseta","value":25000,"tax":0,"ico":0,"taxBase":25000,"total":25000,"currency":"COP","bank":"EFECTY","status":"Pendiente","response":"Esperando pago del cliente en punto de servicio Efecty","autorization":"000000","receipt":"48771830820612","date":"2024-10-17 11:25:48","franchise":"EF","codResponse":3,"codError":"P004","ip":"192.168.32.1","testMode":1,"docType":"CC","document":"12145661231","name":"Ricardo","lastName":"Saldarriaga","email":"ric.salda.94+223@gmail.com","city":"","address":"NA","indCountry":null,"pin":"Prueba-000000","codeProject":110571,"paymentDate":"2024-10-17 11:25:48","expirationDate":"2024-10-20 23:59:59","conversionFactor":4266.2,"pesos":25000,"extras":{"extra1":"45","extra2":"","extra3":"","extra4":"","extra5":"","extra6":"","extra7":"","extra8":"","extra9":"","extra10":""},"extras_epayco":{"extra5":"P37"},"showConversion":1,"token":"eyJwaW4iOiJQcnVlYmEtMDAwMDAwIiwibmFtZXMiOiJSaWNhcmRvIFNhbGRhcnJpYWdhIiwiZGF0ZUV4cGlyYXRpb24iOiIyMDI0LTEwLTIwIDIzOjU5OjU5IiwidHlwZSI6IkVGIiwiY29kUHJvamVjdCI6MTEwNTcxLCJkYXRlIjoiMjAyNC0xMC0xNyAxMToyNTo0OCIsInRybSI6NDI2Ni4yLCJjdXJyZW5jeSI6IkNPUCIsInN1YlRvdGFsIjoyNTAwMCwidGF4IjowLCJpY28iOjAsImFtb3VudCI6MjUwMDAsImNvbXBhbnlOYW1lIjoiWXZvbm5lIEVzY2Fsb25hIiwid2ViIjoiaHR0cHM6XC9cL2VwYXljby5jb20iLCJzaG93Q29udmVyc2lvbiI6MX0="}';
        //$daviplata = json_decode($cash, true);

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
            //$customer = '{"status":true,"success":true,"type":"Create Customer","data":{"status":"exitoso","description":"El cliente fue creado exitosamente con el id: 7095968b9a0f512540e31b8","customerId":"7095968b9a0f512540e31b8","email":"ric.salda.943+1@gmail.com","name":"Jose Alvarez"},"object":"customer"}';
            //$customer = json_decode($customer, true);
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

    /**
     * Set payer transaction
     *
     * @return void
     */
    public function setPayerTransaction(): void
    {
        $payer = $this->transaction->payer;

        $payer->email                  = $this->epayco->orderBilling->getEmail($this->order);
        $payer->first_name             = $this->epayco->orderBilling->getFirstName($this->order);
        $payer->last_name              = $this->epayco->orderBilling->getLastName($this->order);
        $payer->address->city          = $this->epayco->orderBilling->getCity($this->order);
        $payer->address->federal_unit  = $this->epayco->orderBilling->getState($this->order);
        $payer->address->zip_code      = $this->epayco->orderBilling->getZipcode($this->order);
        $payer->address->street_name   = $this->epayco->orderBilling->getFullAddress($this->order);
        $payer->address->street_number = '';
        $payer->address->neighborhood  = '';
    }

    public function string_sanitize($string, $force_lowercase = true, $anal = false) {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]","}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;","â€”", "â€“", ",", "<", ".", ">", "/", "?");
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
