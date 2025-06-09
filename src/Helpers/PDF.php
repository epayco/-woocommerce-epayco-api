<?php

namespace Epayco\Woocommerce\Helpers;
use Epayco\Woocommerce\WoocommerceEpayco;
//use TCPDF;
use Epayco as EpaycoSdk;

if (!defined('ABSPATH')) {
    exit;
}

class PDF
{
    public WoocommerceEpayco $epayco;
    public function __construct()
    {
        global $epayco;
        $this->epayco = $epayco;
    }
    private function getSdkInstance()
    {
        $lang = get_locale();
        $lang = explode('_', $lang);
        $lang = $lang[0];
        $public_key = $this->epayco->sellerConfig->getCredentialsPublicKeyPayment();
        $private_key = $this->epayco->sellerConfig->getCredentialsPrivateKeyPayment();
        $isTestMode = $this->epayco->storeConfig->isTestMode()?"true":"false";
        return new EpaycoSdk\Epayco(
            [
                "apiKey" => $public_key,
                "privateKey" => $private_key,
                "lenguage" => strtoupper($lang),
                "test" => $isTestMode
            ]
        );
    }

    public function download($ref_payco, $order_id, $franchise)
    {
        $order        = wc_get_order($order_id);
        $transactionDetails  =  $this->epayco->orderMetadata->getPaymentsIdMeta($order);
        $paymentInfo = json_decode(wp_json_encode($transactionDetails), true);

        if (empty($paymentInfo)) {
            return;
        }

        if($franchise == 'EF'||
            $franchise == 'GA'||
            $franchise == 'PR'||
            $franchise == 'RS'||
            $franchise == 'SR'
        ){
            if (empty($paymentInfo)) {
                return;
            }
            $paymentsIdArray = explode(', ', $paymentInfo);
            $referencePaycoFilter = ["referencePayco" => $paymentsIdArray[0]];
        }elseif($franchise == 'DP' || $franchise == 'DaviPlata'){
            $transactionDetails  =  $this->epayco->orderMetadata->getDaviplataTransactionDetailsMeta($order);
            $daviplata_data = json_decode($transactionDetails, true);
            if (empty($paymentInfo) && empty($daviplata_data)) {
                return;
            }
            $referenceClient = $daviplata_data['data']['invoice'];
            $referencePaycoFilter = ["referenceClient" => $referenceClient];
        }else{
            $referencePaycoFilter = ["referencePayco" => $ref_payco];
        }
        $data = array(
            "filter" => $referencePaycoFilter,
            "success" =>true
        );

        $epaycoSdk = $this->getSdkInstance();
        $transactionDetails = $epaycoSdk->transaction->get($data,true,"POST");
        $transactionInfo = json_decode(wp_json_encode($transactionDetails), true);

        if (empty($transactionInfo)) {
            return;
        }

        if($franchise == 'DP' || $franchise == 'DaviPlata'){
            if (is_array($transactionInfo)) {
                foreach ((array) $transactionInfo as $transaction) {
                    $daviplataTransactionData["data"] = $transaction;
                }
                $transactionInfo = [
                    "success" => true,
                    "data" => end($daviplataTransactionData["data"])
                ];
            }
        }


        $x_amount = $transactionInfo['data']['x_amount']??$transactionInfo['data']['amount']??$transactionInfo['data'][0]['amount'];
        $x_amount_base = $transactionInfo['data']['x_amount_base']??$transactionInfo['data']['taxBaseClient']??$transactionInfo['data'][0]['taxBaseClient'];
        $x_cardnumber = $transactionInfo['data']['x_cardnumber']??$transactionInfo['data']['numberCard']??$transactionInfo['data'][0]['numberCard'];
        $x_id_invoice = $transactionInfo['data']['x_id_invoice']??$transactionInfo['data']['bill']??$transactionInfo['data'][0]['bill'];
        $x_franchise = $transactionInfo['data']['x_franchise']??$transactionInfo['data']['paymentMethod']??$transactionInfo['data'][0]['paymentMethod']??$transactionInfo['data']['bank']??$transactionInfo['data'][0]['bank'];
        $x_transaction_id = $transactionInfo['data']['x_transaction_id']??$transactionInfo['data']['referencePayco']??$transactionInfo['data'][0]['referencePayco'];
        $x_transaction_date = $transactionInfo['data']['x_transaction_date']??$transactionInfo['data']['transactionDate']??$transactionInfo['data'][0]['transactionDate'];
        $x_transaction_state = $transactionInfo['data']['x_transaction_state']??$transactionInfo['data']['status']??$transactionInfo['data'][0]['status'];
        $x_customer_ip = $transactionInfo['data']['x_customer_ip']??$transactionInfo['data']['ip']??$transactionInfo['data'][0]['ip'];
        $x_description = $transactionInfo['data']['x_description']??$transactionInfo['data']['description']??$transactionInfo['data'][0]['description'];
        $x_response= $transactionInfo['data']['x_response']??$transactionInfo['data']['status']??$transactionInfo['data'][0]['status'];
        $x_response_reason_text= $transactionInfo['data']['x_response_reason_text']??$transactionInfo['data']['response']??$transactionInfo['data'][0]['response'];
        $x_approval_code= $transactionInfo['data']['x_approval_code']??$transactionInfo['data']['authorization']??$transactionInfo['data'][0]['authorization'];
        $x_ref_payco= $transactionInfo['data']['x_ref_payco']??$transactionInfo['data']['referencePayco']??$transactionInfo['data'][0]['referencePayco'];
        $x_tax= $transactionInfo['data']['x_tax']??$transactionInfo['data']['tax']??$transactionInfo['data'][0]['tax'];
        $x_currency_code= $transactionInfo['data']['x_currency_code']??$transactionInfo['data']['currency']??$transactionInfo['data'][0]['currency'];
        $iconBaseUrl = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/';

        switch ($x_response) {
            case 'Aceptada': {
                $iconUrl = $iconBaseUrl.'check.png';
                $iconColor = '#67C940';
                $message = $this->epayco->storeTranslations->epaycoCheckout['success_message'];
            }break;
            case 'Pendiente':
            case 'Pending':{
                $iconUrl = $iconBaseUrl.'warning.png';
                $iconColor = '#FFD100';
                $message = $this->epayco->storeTranslations->epaycoCheckout['pending_message'];
            }break;
            default: {
                $iconUrl = $iconBaseUrl.'error.png';
                $iconColor = '#E1251B';
                $message = $this->epayco->storeTranslations->epaycoCheckout['fail_message'];
            }break;
        }
        $is_cash = false;
        $is_dp = false;
        if($x_franchise == 'EF'||
            $x_franchise == 'GA'||
            $x_franchise == 'PR'||
            $x_franchise == 'RS'||
            $x_franchise == 'SR'
        ){
            $x_cardnumber_ = null;
            $is_cash = true;
            $pin = $paymentsIdArray[1];
            $codeProject = $paymentsIdArray[2];
            $expirationDate = $paymentsIdArray[3];
            $expirationDateText = $this->epayco->storeTranslations->ticketCheckout['expirationDate'];
            $code = $this->epayco->storeTranslations->ticketCheckout['code']??null;
            $ticket_header = $this->epayco->storeTranslations->ticketCheckout['ticket_header'];
            $heigth = 195;
        }else{
            if($x_franchise == 'PSE' ){
                $x_cardnumber_ = null;
            }elseif($x_franchise == 'DP' || $x_franchise == 'DaviPlata'){
                $x_cardnumber_ = null;
                $is_dp = true;
            }else{
                $x_cardnumber_ = isset($x_cardnumber)?substr($x_cardnumber, -8):null;
            }
            $x_franchise = $x_franchise == 'DaviPlata' ? 'DP' : $x_franchise;
        }
        $error_message = $this->epayco->storeTranslations->epaycoCheckout['error_message'];
        $error_description = $this->epayco->storeTranslations->epaycoCheckout['error_description'];
        $epayco_refecence = $this->epayco->storeTranslations->epaycoCheckout['epayco_refecence'];
        $paymentMethod = $this->epayco->storeTranslations->epaycoCheckout['paymentMethod'];
        $authorizations = $this->epayco->storeTranslations->epaycoCheckout['authorization'];
        $franchise_logo = 'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/methods/'.$x_franchise.'.png';
        $authorization = $x_approval_code;
        $receipt = $this->epayco->storeTranslations->epaycoCheckout['receipt'];
        $factura = $x_id_invoice;
        $iPaddress = $this->epayco->storeTranslations->epaycoCheckout['iPaddress'];
        $ip = $x_customer_ip;
        $response_reason_text = $x_response_reason_text;
        $respuesta = $x_response;
        $totalValue = $this->epayco->storeTranslations->epaycoCheckout['totalValue'];
        $description = $this->epayco->storeTranslations->epaycoCheckout['description'];
        $reference = $this->epayco->storeTranslations->epaycoCheckout['reference'];
        $purchase = $this->epayco->storeTranslations->epaycoCheckout['purchase'];
        $response = $this->epayco->storeTranslations->epaycoCheckout['response'];


        try {
            if ($is_cash) {
                $cashHtml = '
                <div class="parDescription">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="vertical-align:top; width:50%; padding-right:10px; padding-top:10px;">
                                <div class="titleAndText">
                                    <div class="h3Facture" style="color:grey">'.$response.'</div>
                                    <div class="pFacture">'.$response_reason_text.'</div>
                                </div>
                            </td>
                            <td style="vertical-align:top; width:50%; padding-left:10px; padding-top:10px;">
                                <div class="titleAndTextRight">
                                    <div class="h3Facture" style="color:grey">'.$expirationDateText.'</div>
                                    <div class="pFacture">'.$expirationDate.'</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="parDescription" style="padding-top:10px;">
                    <div class="pFacture">'.$ticket_header.'</div>
                </div>
                <div class="parDescription">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="vertical-align:top; width:50%; padding-right:10px; padding-top:10px;">
                                <div class="titleAndText">
                                    <div class="h3Facture" style="color:grey">'.$code.'</div>
                                    <div class="pFacture">'.$codeProject.'</div>
                                </div>
                            </td>
                            <td style="vertical-align:top; width:50%; padding-left:10px; padding-top:10px;">
                                <div class="titleAndTextRight">
                                    <div class="h3Facture" style="color:grey">Pin</div>
                                    <div class="pFacture">'.$pin.'</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                ';
                        }else{
                            $cashHtml = '
                <div class="parDescription">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="vertical-align:top; width:50%; padding-right:10px; padding-top:10px;">
                                <div class="titleAndText">
                                    <div class="h3Facture" style="color:grey">'.$response.'</div>
                                    <div class="pFacture">'.$response_reason_text.'</div>
                                </div>
                            </td>
                            <td style="vertical-align:top; width:50%; padding-left:10px; padding-top:10px;">
                                <div class="titleAndTextRight">
                                    <div class="h3Facture" style="color:grey"></div>
                                    <div class="pFacture"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                ';
                        }
                        $html = '
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
            <style>
            body, .landingResumen {
                max-height: 95vh;
                overflow: hidden;
                font-family: "poppins", Arial, sans-serif;
            }
            .landingResumen {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            }
            .navEpayco {
                display: flex;
                justify-content: center;   /* Centra horizontalmente */
                align-items: center;       /* Centra verticalmente */
                height: 40px;              /* Alto del contenedor */
                background: #1d1d1d;
                text-align: center;
            }
            .navEpayco img {
                display: block;
                margin: 0 auto;
                max-height: 40px;          /* Ajusta el tamaño del logo si es necesario */
                margin-top: 10px !important; /* Espacio superior */
            }
            .containerResumen {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                height: fit-content;
                gap: 1rem;
                padding-top: 3rem;
                padding-bottom: 4.8rem;
                width: 100%;
            }
            .hole {
                padding-top: 1.6rem;
                overflow: visible;
                width: 557px;
                height: 0px;
                border-radius: 1.6rem;
                background: #1d1d1d;
                margin-left: auto;
                margin-right: auto;
                position: relative;
                z-index: 1;
            }
            .containerFacture {
                position: relative;
                align-items: center;
                transform: translateY(-1.95rem);
                flex-direction: column;
                background: #f9f9f9;
                height: fit-content;
                width: 490px;
                padding: 32px 24px 40px;
                gap: 18px;
                /*box-shadow: 0 8px 16px 0 rgba(0, 0, 0, .08);*/
                border-radius: 0 0 10px 10px;
                border-right: 1px solid #cacaca;
                border-bottom: 1px solid #cacaca;
                border-left: 1px solid #cacaca;
                top: 5px;
                display: flex;
                justify-content: center;
                margin-left: auto;   /* <-- centra horizontalmente */
                margin-right: auto;  /* <-- centra horizontalmente */
                margin-top: -10px;
                margin-bottom: 40px;
                z-index: 2;
                }
            .transaction {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: #1d1d1d;
                gap: 1.6rem;
                text-align: center;
                margin-left: auto;   /* <-- centra horizontalmente */
                margin-right: auto;  /* <-- centra horizontalmente */
            }
            .transactionText {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: .5rem;
            }
            .h1Bold {
                font-weight: 600;
            }
            .h1Facture {
                font-size: 24px;
                display: block;
                font-style: normal;
                font-weight: bold;
                line-height: normal;
            }
            .pFacture {
                font-size: 16px;
                color: #000;
            }
            .h3Facture,
            .pFacture {
                font-style: normal;
                font-weight: 400;
                line-height: normal;
            }
            .medioPago,
            .medios {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                margin-left: auto;   /* <-- centra horizontalmente */
                margin-right: auto;  /* <-- centra horizontalmente */
            }
            .medioPago {
                width: 100%;
                justify-content: center;
                align-items: center;
                margin-left: auto;   /* <-- centra horizontalmente */
                margin-right: auto;  /* <-- centra horizontalmente */
            }
            .medios {
                width: 380px;
                align-items: flex-start;
            }
            .h2Facture {
                font-size: 16px;
                display: block;
                font-weight: 700;
            }
            .parDescription {
                width: 100%;
                display: flex;
                justify-content: center; /* Centra horizontalmente el contenido */
                align-items: flex-start;
                margin: 0 auto;
            }
            .parDescription table {
                margin: 0 auto; /* Centra la tabla dentro del div */
            }
            .titleAndText,
            .titleAndTextRight {
                width: 100%; /* Ocupa todo el espacio de la columna */
                box-sizing: border-box;
                padding: 0;
            }
            
            .titleAndTextComplete {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            .pageAndImage {
                display: flex;
                flex-direction: row;      /* Por defecto, pero lo aclaramos */
                align-items: center;      /* Centra verticalmente */
                gap: 1rem;                /* Espacio entre logo y número */
                width: fit-content;
                justify-content: flex-start;
            }
            .metodoPago {
                align-items: center;
                height: 1.5rem !important;
            }
            </style>
            
            <div class="landingResumen">
                <div class="navEpayco">
                    <img src="https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/epayco-blanco.png" alt="logo" >
                </div>
                <div class="containerResumen">
                    <div class="hole"></div>
                    <div class="containerFacture">
                        <div class="transaction">
                            <img src="'.$iconUrl.'" alt="check" style="display: block; margin: auto; border-bottom: 25px;">
                            <div class="transactionText">
                                <div class="h1Facture h1Bold" style="color:'.$iconColor.'">
                                    '.$message.'
                                </div>
                                <div class="h1Facture">
                                    <h2 style="font-size: 22px; font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; font-weight: bold">'.$epayco_refecence.' #'.$x_ref_payco.'</h2>
                                </div>
                                <div class="pFacture">
                                    '.$x_transaction_date.'
                                </div>
                            </div>
                        </div>
                        <div class="medioPago">
                            <div class="medios" style="padding-top:10px; margin-left: 20%;"> 
                                <div class="h2Facture">'.$paymentMethod.'</div>
                                <div class="parDescription" style="width:100%;">
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tr>
                                        <td style="vertical-align:top; width:50%; padding-right:10px; padding-top:10px;">
                                            <div class="titleAndText">
                                                <div class="h3Facture" style="color:grey">'.$paymentMethod.'</div>
                                                <div class="pageAndImage">
                                                <img class="metodoPago" src="'.$franchise_logo.'" id="metodoPagoId" alt="logoTransacción">
                                                  <!--<div class="pFacture">'.$x_cardnumber.'</div>-->
                                                </div>
                                            </div>
                                        </td>
                                        <td style="vertical-align:top; width:50%; padding-left:10px; padding-top:10px;">
                                            <div class="titleAndTextRight">
                                                <div class="h3Facture" style="color:grey">'.$authorizations.'</div>
                                                <div class="pFacture">'.$authorization.'</div>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                </div>
             
                                <div class="parDescription">    
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tr>
                                        <td style="vertical-align:top; width:50%; padding-right:10px; padding-top:10px;">
                                            <div class="titleAndText">
                                                <div class="h3Facture" style="color:grey">'.$receipt.'</div>
                                                <div class="pFacture">'.$factura.'</div>
                                            </div>
                                        </td>
                                        <td style="vertical-align:top; width:50%; padding-left:10px; padding-top:10px;">
                                            <div class="titleAndTextRight">
                                                <div class="h3Facture" style="color:grey">'.$iPaddress.'</div>
                                                <div class="pFacture">'.$ip.'</div>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                </div>'.$cashHtml.'
                                
                            <div class="medios" style="margin-top: 10px">
                                <div class="h2Facture">'.$purchase.'</div>
                                <div class="parDescription">
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tr>
                                        <td style="vertical-align:top; width:50%; padding-right:10px; padding-top:10px;">
                                            <div class="titleAndText">
                                                <div class="h3Facture" style="color:grey">'.$reference.'</div>
                                                <div class="pFacture">'.$x_ref_payco.'</div>
                                            </div>
                                        </td>
                                        <td style="vertical-align:top; width:50%; padding-left:10px; padding-top:10px;">
                                            <div class="titleAndTextRight">
                                                <div class="h3Facture" style="color:grey">'.$description.'</div>
                                                <div class="pFacture">'.$x_description.'</div>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="parDescription">
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tr>
                                        <td style="vertical-align:top; width:50%; padding-right:10px; padding-top:10px;">
                                            <div class="titleAndText">
                                                <div class="h3Facture" style="color:grey">'.$totalValue.'</div>
                                                <div class="pFacture">$'.$x_amount.' '.$currency.'</div>
                                            </div>
                                        </td>
                                        <td style="vertical-align:top; width:50%; padding-left:10px; padding-top:10px;">
                                            <div class="titleAndTextRight">
                                                <div class="h3Facture" style="color:grey">Subtotal</div>
                                                <div class="pFacture">$'.$x_amount_base.' '.$currency.'</div>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
            ]);
            $mpdf->SetDisplayMode('fullpage');
            //$mpdf->SetImportUse();
            $mpdf->WriteHTML($html);
            $orden_id  = 1;
            $path = "factura-orden-{$orden_id}.pdf";
            $mpdf->Output($path, \Mpdf\Output\Destination::INLINE);
        } catch (\Exception $err) {
            echo $err->getMessage();
        }
        exit;

    }

    private function getDPInfo($epaycoSdk, $data, $daviplata_data)
    {
        $referenceClient = $daviplata_data['data']['invoice'];
        $bodyRequest= [
            "filter"=>[
                "referenceClient"=>$referenceClient
            ]
        ];
        $transactionDetails = $epaycoSdk->transaction->get($data,true,"POST");
        $transactionInfo = json_decode(wp_json_encode($transactionDetails), true);
        if (empty($transactionInfo)) {
            return;
        }

        if (is_array($transactionInfo)) {
            foreach ((array) $transactionInfo as $transaction) {
                $daviplataTransactionData["data"] = $transaction;
            }
        }
        $daviplataTransaction = [
            "success" => true,
            "data" => end($daviplataTransactionData["data"])
        ];
        if(is_array($daviplataTransaction['data'])){
            $_transaction = $daviplataTransaction;
        }elseif(is_array($transactionInfo)){
            $_transaction = $transactionInfo;
        }else{
            return;
        }
        return $_transaction;
    }
}