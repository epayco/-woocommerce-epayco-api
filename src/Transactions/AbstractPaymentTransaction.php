<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Gateways\AbstractGateway;

abstract class AbstractPaymentTransaction extends AbstractTransaction
{
    /**
     * Payment Transaction constructor
     */
    public function __construct(AbstractGateway $gateway, ?\WC_Order $order, array $checkout)
    {
        parent::__construct($gateway, $order, $checkout);
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
        $basedCountry = WC()->countries->get_base_country()!='' ? WC()->countries->get_base_country():$order->get_shipping_country();
        //$basedCountry = $checkout["countryType"]??$checkout["countrytype"];
        $city = WC()->countries->get_base_city() !='' ? WC()->countries->get_base_city():$order->get_shipping_city();
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["confirm_url"];
        $end_date = date('y-m-d', strtotime(sprintf('+%s days',$checkout["date_expiration"]) ));
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        //$person_type= $checkout["person_type"];
        $person_type= 'PN';
        //$holder_address= $checkout["address"];
        $holder_address=$order->get_billing_address_1();
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"]??$checkout["documentType"];
        $doc_number= $checkout["doc_number"]??$checkout["document"]??$checkout[""]["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"];
        $cellphone= $checkout["cellphonetype"];
        //$cellphone=@$order->billing_phone??'0';
        $data = array(
            "paymentMethod" => $checkout["paymentMethod"],
            "invoice" => (string)$order->get_id()."_wc_api_test".(string)$order->get_id(),
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
        //$cash = json_decode('{"success":true,"titleResponse":"SUCCESS","textResponse":"Transacci\u00f3n y pin generados exitosamente","lastAction":"Crear pin efecty","data":{"refPayco":101654588,"invoice":"61_wc_api_test61","description":"camisa","value":23000,"tax":0,"ico":0,"taxBase":23000,"total":23000,"currency":"COP","bank":"EFECTY","status":"Pendiente","response":"Esperando pago del cliente en punto de servicio Efecty","autorization":"000000","receipt":"48771839236165","date":"2025-01-22 16:32:57","franchise":"EF","codResponse":3,"codError":"P004","ip":"192.168.32.1","testMode":1,"docType":"CC","document":"1232311111","name":"ricardo","lastName":"saldarriaga","email":"ric.salda.94@gmail.com","city":"","address":"NA","indCountry":null,"pin":"Prueba-000000","codeProject":110571,"paymentDate":"2025-01-22 16:32:57","expirationDate":"2025-01-31 23:59:59","conversionFactor":4344.27,"pesos":23000,"extras":{"extra1":"61","extra2":"","extra3":"","extra4":"","extra5":"","extra6":"","extra7":"","extra8":"","extra9":"","extra10":""},"extras_epayco":{"extra5":"P37"},"showConversion":1,"token":"eyJwaW4iOiJQcnVlYmEtMDAwMDAwIiwibmFtZXMiOiJyaWNhcmRvIHNhbGRhcnJpYWdhIiwiZGF0ZUV4cGlyYXRpb24iOiIyMDI1LTAxLTMxIDIzOjU5OjU5IiwidHlwZSI6IkVGIiwiY29kUHJvamVjdCI6MTEwNTcxLCJkYXRlIjoiMjAyNS0wMS0yMiAxNjozMjo1NyIsInRybSI6NDM0NC4yNywiY3VycmVuY3kiOiJDT1AiLCJzdWJUb3RhbCI6MjMwMDAsInRheCI6MCwiaWNvIjowLCJhbW91bnQiOjIzMDAwLCJjb21wYW55TmFtZSI6Ik9zbmVpZGVyIENhcnJlXHUwMGYxbyBIZXJyZXJhIiwid2ViIjoiIiwic2hvd0NvbnZlcnNpb24iOjF9"}}',true);
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
        //$basedCountry = WC()->countries->get_base_country();
        $basedCountry = 'CO';
        $myIp=$this->getCustomerIp();
        $confirm_url = $checkout["confirm_url"];
        $response_url = $checkout["response_url"];
        $customerName = $checkout["name"]??$checkout[""]["name"];
        $explodeName = explode(" ", $customerName);
        $name = $explodeName[0];
        $lastName = $explodeName[1];
        //$person_type= $checkout["person_type"]??$checkout[""]["person_type"];
        //$holder_address= $checkout["address"]??$checkout[""]["address"];
        $person_type= 'PN';
        $holder_address=$order->get_billing_address_1();
        $doc_type= $checkout["identificationtype"]??$checkout["identificationType"];
        $doc_number= $checkout["doc_number"]??$checkout[""]["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphonetype"]??$checkout[""]["cellphonetype"];
        $cellphonetype = $_POST["cellphone"]??$checkout["cellphone"]??$checkout[""]["cellphone"];
        $cellphonetypeIn = explode("+", $cellphonetype)[1];
        $city = WC()->countries->get_base_city() !='' ? WC()->countries->get_base_city():$order->get_shipping_city();
        $testMode = $this->epayco->storeConfig->isTestMode()??false;
        $data = array(
            "invoice" => (string)$order->get_id()."_wc_api_test_".(string)$order->get_id(),
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
            //"urlResponse" => $response_url,
            "urlConfirmation" => $confirm_url,
            "methodConfirmation" => "GET",
            "extra1" => (string)$order->get_id(),
            "vtex" => true,
            "testMode" => $testMode,
            "extras_epayco"=>["extra5"=>"P19"]
        );
        $daviplata = $this->sdk->daviplata->create($data);
        $daviplata= json_decode(json_encode($daviplata), true);
        return $daviplata;
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
        $doc_number= $checkout["doc_number"]??$checkout[""]["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphone"]??$checkout[""]["cellphone"];
        /*$customerData = $this->getCustomer($checkout);
        if(!$customerData['success']){
            return $customerData;
        }*/
        $data = array(
            "token_card" => $checkout["token"],
            //"customer_id" => $customerData['customer_id'],
            "customer_id" => 'customer_id',
            "bill" => (string)$order->get_id()."_wc_api_test",
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
        $doc_number= $checkout["doc_number"]??$checkout[""]["doc_number"]??$_POST['docNumberError']??$_POST['identificationTypeError'];
        $email= $checkout["email"]??$checkout[""]["email"];
        $cellphone= $checkout["cellphonetype"]??$checkout[""]["cellphonetype"];
        $data = array(
            "bank" => $bank,
            "invoice" => (string)$order->get_id()."_wc_api_test",
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
            "methodConfirmation" => "GET",
            "extra1" => (string)$order->get_id(),
            "testMode" => $testMode,
            "extras_epayco"=>["extra5"=>"P58"]
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
    public function createSubscriptionPayment($order_id, array $checkout)
    {
        global $wpdb;
        $order = new \WC_Order($order_id);
        $subscriptions = wcs_get_subscriptions_for_order($order_id);
        $customerData = $this->getCustomer($checkout);
        if(!$customerData['success']){
            return $customerData;
        }
        $checkout['customer_id'] = $customerData['customer_id'];
        $customer = $this->paramsBilling($subscriptions, $order, $checkout);
        $plans = $this->getPlansBySubscription($subscriptions);
        $getPlans = $this->getPlans($plans);
        if (!$getPlans) {
            $validatePlan = $this->validatePlan(true, $order_id, $plans, $subscriptions, $customer, $order, false, false, null,$checkout);
        } else {
            $validatePlan = $this->validatePlan(false, $order_id, $plans, $subscriptions, $customer, $order, true, false, $getPlans,$checkout);
        }
        $errorMessage = array();
        if(!$validatePlan['success']){
            if(is_array($validatePlan['message'])){
                foreach ($validatePlan['message'] as $message) {
                    $errorMessage[] = $message;
                }
                return [
                    'success' => false,
                    'message' => implode(' - ', $errorMessage)
                ];
            }else{
                return [
                    'success' => false,
                    'message' => $validatePlan['message']
                ];
            }
        }else{
            return $validatePlan;
        }
    }

    public function getCustomer($customerData)
    {
        global $wpdb;
        $table_name_customer = $wpdb->prefix . 'epayco_customer';
        $customerGetData = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name_customer WHERE email = %s",
                trim($customerData['email'])
            )
        );
        if (count($customerGetData) == 0) {
            $customer = $this->customerCreate($customerData);
            if (is_array($customer) && $customer['success']) {
                $inserCustomer = $wpdb->insert(
                    $table_name_customer,
                    [
                        'customer_id' => $customer['data']['customerId'],
                        //'token_id' => trim($customerData['token']),
                        'email' => trim($customerData['email'])
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
                if ($customerGetData[$i]->email == trim($customerData['email'])) {
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
                        //'token_id' => trim($customerData['token']),
                        'email' => trim($customerData['email'])
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
                $count_cards = 0;
                for ($i = 0; $i < count($customerGetData); $i++) {
                    $customers = $this->sdk->customer->get($customerGetData[$i]->customer_id);
                    $customers = json_decode(json_encode($customers), true);
                    if($customers['success']){
                        $cards = $customers['data']['cards'];
                        for ($j = 0; $j < count($cards); $j++) {
                            if ($cards[$j]['token'] == trim($customerData['token'])) {
                                $count_customers += 1;
                            }
                        }
                        if($count_customers == 0){
                            $this->customerAddToken($customerGetData[$i]->customer_id, trim($customerData['token']));
                        }
                    }
                    /*if ($customerGetData[$i]->email == trim($customerData['email']) && $customerGetData[$i]->token_id != trim($customerData['token'])) {
                        $this->customerAddToken($customerGetData[$i]->customer_id, trim($customerData['token']));
                    }*/
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
                    "country" => $data['countrytype'],
                    "address" => $data['address'],
                    "default" => true
                ]
            );
            $customer = json_decode(json_encode($customer), true);
            return $customer;
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => 'create client: : ' . $exception->getMessage()
            ];
        }
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
            return $customer;
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => 'add token: ' . $exception->getMessage()
            ];
        }
    }

    public function getPlansBySubscription(array $subscriptions)
    {
        $plans = [];
        foreach ($subscriptions as $key => $subscription) {
            $total_discount = $subscription->get_total_discount();
            $order_currency = $subscription->get_currency();
            $products = $subscription->get_items();
            $product_plan = $this->getPlan($products);
            $quantity = $product_plan['quantity'];
            $product_name = $product_plan['name'];
            $product_id = $product_plan['id'];
            $trial_days = $this->getTrialDays($subscription);
            $plan_code = "$product_name-$product_id";
            $plan_code = $trial_days > 0 ? "$product_name-$product_id-$trial_days" : $plan_code;
            //$plan_code = $this->currency !== $order_currency ? "$plan_code-$order_currency" : $plan_code;
            $plan_code = $quantity > 1 ? "$plan_code-$quantity" : $plan_code;
            $plan_code = $total_discount > 0 ? "$plan_code-$total_discount" : $plan_code;
            $plan_code = rtrim($plan_code, "-");
            $plan_id = str_replace(array("-", "--"), array("_", ""), $plan_code);
            $plan_name = trim(str_replace("-", " ", $product_name));
            $plans[] = array_merge(
                [
                    "id_plan" => strtolower(str_replace("__", "_", $plan_id)),
                    "name" => "Plan $plan_name",
                    "description" => "Plan $plan_name",
                    "currency" => $order_currency,
                ],
                [
                    "trial_days" => $trial_days
                ],
                $this->intervalAmount($subscription)
            );
        }
        return $plans;
    }

    public function getPlan($products)
    {
        $product_plan = [];

        $product_plan['name'] = '';
        $product_plan['id'] = 0;
        $product_plan['quantity'] = 0;

        foreach ($products as $product) {
            $product_plan['name'] .= "{$product['name']}-";
            $product_plan['id'] .= "{$product['product_id']}-";
            $product_plan['quantity'] .= $product['quantity'];
        }

        $product_plan['name'] = $this->cleanCharacters($product_plan['name']);

        return $product_plan;
    }

    public function validatePlan($create, $order_id, array $plans, $subscriptions, $customer, $order, $confirm, $update, $getPlans, $checkout)
    {
        if ($create) {
            $newPLan = $this->plansCreate($plans);
            if ($newPLan->status) {
                $getPlans_ = $this->getPlans($plans);
                if ($getPlans_) {
                    $eXistPLan = $this->validatePlanData($plans, $getPlans_, $order_id, $subscriptions, $customer, $order, $checkout);
                } else {
                    $this->validatePlan(true, $order_id, $plans, $subscriptions, $customer, $order, false, false, null,$checkout);
                }
            } else {
                $response_status = [
                    'status' => false,
                    'message' => $newPLan->message??$newPLan['message']
                ];
                return $response_status;
            }
        } else {
            if ($confirm) {
                $eXistPLan = $this->validatePlanData($plans, $getPlans, $order_id, $subscriptions, $customer, $order, $checkout);
            }
        }
        return $eXistPLan;
    }

    public function plansCreate(array $plans)
    {
        foreach ($plans as $plan) {
            try {
                $plan_ = $this->sdk->plan->create(
                    [
                        "id_plan" => (string)str_replace("-", "_",strtolower($plan['id_plan'])),
                        "name" => (string)$plan['name'],
                        "description" => (string)$plan['description'],
                        "amount" => $plan['amount'],
                        "currency" => $plan['currency'],
                        "interval" => $plan['interval'],
                        "interval_count" => $plan['interval_count'],
                        "trial_days" => $plan['trial_days']
                    ]
                );
                return $plan_;
            } catch (\Exception $exception) {
                return  [
                    'status' => false,
                    'message' => "create Plan: ".$exception->getMessage()
                ];
            }
        }
    }

    public function validatePlanData($plans, $getPlans, $order_id, $subscriptions, $customer, $order,$checkout)
    {
        foreach ($plans as $plan) {
            $plan_amount_cart = $plan['amount'];
            $plan_id_cart = (string)str_replace("-", "_",strtolower($plan['id_plan']));
        }
        $plan_amount_epayco = $getPlans->plan->amount;
        $plan_id_epayco = (string)str_replace("-", "_",strtolower($getPlans->plan->id_plan));
        if ($plan_id_cart == $plan_id_epayco) {
            try {
                if (intval($plan_amount_cart) == $plan_amount_epayco) {
                    return $this->process_payment_epayco($plans, $customer, $subscriptions, $order, $checkout);
                } else {
                    return $this->validateNewPlanData($plans, $order_id, $subscriptions,$customer, $order, $checkout);
                }
            } catch (\Exception $exception) {
                return [
                    'status' => false,
                    'message' => $exception->getMessage()
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'el id del plan creado no concuerda!'
            ];
        }
    }

    public function validateNewPlanData($plans, $order_id, $subscriptions,$customer, $order, $checkout)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'epayco_plans';
        $wc_order_product_lookup = $wpdb->prefix . "wc_order_product_lookup";
        /*valida la actualizacion del precio del plan*/
        foreach ($subscriptions as $key => $subscription) {
            $products = $subscription->get_items();
            $product_plan = $this->getPlan($products);
            $product_id_ = $product_plan['id'];
            $porciones = explode("-", $product_id_);
            $product_id = $porciones[0];
        }
        $currency = strtolower(get_woocommerce_currency());
        $plan_currency = strtolower($plans[0]['currency']);
        if($currency == $plan_currency){
            $newPlans[] = [
                'id_plan' => $product_plan['name'].$product_plan['id'].$plans[0]['amount'],
                'name' => $plans[0]['name'],
                'description' => $plans[0]['description'],
                'currency' => $plans[0]['currency'],
                'trial_days' => $plans[0]['trial_days'],
                'interval' => $plans[0]['interval'],
                'amount' => $plans[0]['amount'],
                'interval_count' => $plans[0]['interval_count']
            ];
            $getPlans = $this->getPlans($newPlans);
            if(!$getPlans){
                return $this->validatePlan(true, $order_id, $newPlans, $subscriptions, $customer, $order, false, false, null,$checkout);
            }else{
                return $this->validatePlan(false, $order_id, $newPlans, $subscriptions, $customer, $order, true, false, $getPlans,$checkout);
            }
        }

    }

    public function getTrialDays(\WC_Subscription $subscription)
    {
        $trial_days = "0";
        $trial_start = $subscription->get_date('start');
        $trial_end = $subscription->get_date('trial_end');

        if ($trial_end > 0)
            $trial_days = (string)(strtotime($trial_end) - strtotime($trial_start)) / (60 * 60 * 24);

        return $trial_days;
    }

    public function intervalAmount(\WC_Subscription $subscription)
    {
        return [
            "interval" => $subscription->get_billing_period(),
            "amount" => $subscription->get_total(),
            "interval_count" => $subscription->get_billing_interval()
        ];
    }

    public function getPlans(array $plans)
    {
        foreach ($plans as $key => $plan) {
            try {
                $planId = str_replace("-", "_",strtolower($plans[$key]['id_plan']));
                $plan = $this->sdk->plan->get($planId);
                if ($plan->status) {
                    unset($plans[$key]);
                    return $plan;
                } else {
                    return false;
                }

            } catch (\Exception $exception) {
                return false;
            }
        }
    }

    public function cleanCharacters($string)
    {
        $string = str_replace(' ', '-', $string);
        $patern = '/[^A-Za-z0-9\-]/';
        return preg_replace($patern, '', $string);
    }

    public function paramsBilling($subscriptions, $order, $checkout)
    {
        $data = [];
        $subscription = end($subscriptions);
        if ($subscription) {
            $data['token_card'] = $checkout['token'];
            $data['customer_id'] = $checkout['customer_id'];
            $data['name'] = $checkout['name'];
            $data['email'] = $checkout['email'];
            $data['phone'] = $checkout['cellphone'];
            $data['country'] = $checkout['countrytype'];
            $data['city'] = $checkout['country'];
            $data['address'] = $checkout['address'];
            $data['doc_number'] = $checkout['doc_number'];
            $data['type_document'] = $checkout['identificationtype'];
            return $data;
        } else {
            $redirect = array(
                'result' => 'fail',
                'redirect' => add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
            );
            wc_add_notice('EL producto que intenta pagar no es permitido', 'error');
            wp_redirect($redirect["redirect"]);
            die();
        }

    }

    public function process_payment_epayco(array $plans, array $customerData, $subscriptions, $order, $checkout)
    {
        $subsCreated = $this->subscriptionCreate($plans, $customerData, $checkout);
        //$subsCreated = json_decode(json_encode('{"status":true,"message":"Suscripci\u00f3n creada","created":"06-11-2024","id":"72b74c1bc789d28620a5196","success":true,"current_period_start":"11\/06\/2024","current_period_end":"06-11-2024","customer":{"_id":"72a6e60670f068ec00241b8","name":"ricardo saldarriaga","email":"ricardo.saldarriaga@payco.co","doc_number":"1232323111","merchantId":"627236","indicative":"","country":"CO","city":"","address":"calle 109 # 67-112","break_card":false,"doc_type":"CC","updated_at":"2024-11-06T13:53:25.556000Z"},"status_subscription":"inactive","type":"Create Subscription","data":{"idClient":"coffe_020_15000","name":"Plan coffe","description":"Plan coffe","amount":15000,"currency":"COP","interval":"month","interval_count":"1","trialDays":0,"createdAt":"2024-11-05T23:02:01.926000Z"},"object":"subscription"}'), true);
        //$subsCreated = json_decode($subsCreated);
        if ($subsCreated->status) {
            $subs = $this->subscriptionCharge($plans, $customerData, $checkout);
            //$subs = json_decode(json_encode('[{"success":true,"title_response":"Transacci\u00f3n realizada","text_response":"Transaccion realizada con tarjeta de pruebas","last_action":"Validar tarjeta de pruebas","data":{"ref_payco":101645839,"factura":"72b74c1bc789d28620a5196-1730902023","descripcion":"Plan coffe","valor":15000,"iva":0,"ico":0,"baseiva":15000,"valorneto":15000,"moneda":"COP","banco":"BANCO DE PRUEBAS","estado":"Aceptada","respuesta":"Aprobada","autorizacion":"000000","recibo":"101645839","fecha":"2024-11-06 09:07:05","franquicia":"VS","cod_respuesta":1,"cod_error":"00","ip":"192.168.32.1","enpruebas":1,"tipo_doc":"CC","documento":"1232323111","nombres":"ricardo","apellidos":"saldarriaga","email":"ricardo.saldarriaga@payco.co","ciudad":"SIN CIUDAD","direccion":"calle 109 # 67112","ind_pais":"PE","country_card":"PE","extras":{"extra1":"72b74c1bc789d28620a5196","extra2":"72a6e60670f068ec00241b8","extra3":"72aa3e96af64966850cc489","extra9":"627236","extra4":"","extra5":"","extra6":"","extra7":"","extra8":"","extra10":""},"cc_network_response":{"code":"00","message":"Aprobada"},"extras_epayco":{"extra5":"P10"}},"subscription":{"idPlan":"coffe_020_15000","data":{"idClient":"coffe_020_15000","name":"Plan coffe","description":"Plan coffe","amount":15000,"currency":"COP","interval":"month","interval_count":"1","trialDays":0},"periodStart":"2024-11-06T08:53:05.000000Z","periodEnd":"06-12-2024","nextVerificationDate":"06-12-2024","status":"active","first":true,"idCustomer":"72a6e60670f068ec00241b8","tokenCard":"72b71096c1e3afd5f035db6","ip":"192.168.32.1","paymentAttempts":[],"url_confirmation":"http:\/\/localhost:86\/wordpress\/?wc-api=WC_Epayco_Subscription_Gateway&order_id=73&confirmation=1","method_confirmation":"POST"}}]'), true);
            //$subs = json_decode($subs);
            foreach ($subs as $sub) {
                $validation = !is_null($sub->status) ? $sub->status : $sub->success;
                if ($validation) {
                    $messageStatus = $this->handleStatusSubscriptions($subs, $subscriptions);
                    return $messageStatus;
                } else {
                    $errorMessage = $sub->data->errors;
                    $response_status = [
                        'success' => false,
                        'message' => $errorMessage
                    ];
                }
            }
        } else {
            $errorMessage = $subsCreated->data->description;
            $response_status = [
                'success' => false,
                'message' => $errorMessage,
            ];
        }
        return $response_status;
    }

    public function subscriptionCreate(array $plans, array $customer, $checkout)
    {
        $confirm_url = $checkout["confirm_url"];
        foreach ($plans as $plan) {
            try {
                $planId = str_replace("-", "_",strtolower($plan['id_plan']));
                $suscriptioncreted = $this->sdk->subscriptions->create(
                    [
                        "id_plan" => $planId,
                        "customer" => $customer['customer_id'],
                        "token_card" => $customer['token_card'],
                        "doc_type" => $customer['type_document'],
                        "doc_number" => $customer['doc_number'],
                        "url_confirmation" => $confirm_url,
                        "method_confirmation" => "POST"
                    ]
                );

                return $suscriptioncreted;

            } catch (\Exception $exception) {
                return [
                    'status' => false,
                    'message' => "subscriptionCreate ".$exception->getMessage()
                ];
            }
        }
    }

    public function subscriptionCharge(array $plans, array $customer, $checkout)
    {
        $subs = [];
        $confirm_url = $checkout["confirm_url"];
        foreach ($plans as $plan) {
            try {
                $planId = str_replace("-", "_",strtolower($plan['id_plan']));
                $subs[] = $this->sdk->subscriptions->charge(
                    [
                        "id_plan" => $planId,
                        "customer" => $customer['customer_id'],
                        "token_card" => $customer['token_card'],
                        "doc_type" => $customer['type_document'],
                        "doc_number" => $customer['doc_number'],
                        "ip" => $this->getCustomerIp(),
                        "url_confirmation" => $confirm_url,
                        "method_confirmation" => "POST"
                    ]
                );

            } catch (\Exception $exception) {
                return [
                    'status' => false,
                    'message' => "subscriptionCharge ".$exception->getMessage()
                ];
            }
        }

        return $subs;
    }

    public function handleStatusSubscriptions(array $subscriptionsStatus, array $subscriptions)
    {
        $count = 0;
        $messageStatus = [];
        $messageStatus['status'] = true;
        $messageStatus['success'] = false;
        $messageStatus['message'] = [];
        $messageStatus['estado'] = [];
        $messageStatus['ref_payco'] = [];
        $quantitySubscriptions = count($subscriptionsStatus);
        $orederStatus = array("Aprobada", "Aceptada", "Pendiente");
        foreach ($subscriptions as $subscription) {
            $sub = $subscriptionsStatus[$count];
            $messageStatus['ref_payco'] = array_merge($messageStatus['ref_payco'], [$sub->data->ref_payco]);
            $messageStatus['message'] = array_merge($messageStatus['message'], ["estado: {$sub->data->respuesta}"]);
            $messageStatus['estado'] = array_merge($messageStatus['estado'], [$sub->data->respuesta]);
            if(in_array($sub->data->respuesta, $orederStatus)){
                $messageStatus['success'] = true;
            }
            $count++;

            //if ($count === $quantitySubscriptions && count($messageStatus['message']) >= $count)
            //$messageStatus['success'] = $subscriptionsStatus[$count]->success;
        }
        return $messageStatus;

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