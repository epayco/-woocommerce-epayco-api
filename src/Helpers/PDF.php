<?php

namespace Epayco\Woocommerce\Helpers;
use Epayco\Woocommerce\WoocommerceEpayco;
use TCPDF;
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
        $colores = [
            'aceptada' => [103, 201, 64],
            'rechazada' => [225, 37, 27],
            'pendiente' => [255, 209, 0],
        ];
        $color = $colores[strtolower($x_transaction_state)] ?? [0, 0, 0];

        $titulo = 'Transacción ' . ucfirst(strtolower($x_transaction_state));

        $description_ = $x_description;

        $heigth = 180;

        switch ($x_response) {
            case 'Aceptada': {
                $iconUrl = $iconBaseUrl.'check.png';
                $iconColor = $color;
                $message = $this->epayco->storeTranslations->epaycoCheckout['success_message'];
            }break;
            case 'Pendiente':
            case 'Pending':{
                $iconUrl = $iconBaseUrl.'warning.png';
                $iconColor =$color;
                $message = $this->epayco->storeTranslations->epaycoCheckout['pending_message'];
            }break;
            default: {
                $iconUrl = $iconBaseUrl.'error.png';
                $iconColor = $color;
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

        //$pdf = new TCPDF();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Header negro con logo
        $pdf->SetFillColor(29, 29, 29);
        $pdf->Rect(0, 0, 210, 20, 'F');
        //$pdf->Image('https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/epayco-blanco.png', 80, 5, 50, 10, '', '', '', false, 300, '', false, false, 0);
        $pdf->Image(
            'https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/epayco-blanco.png',
            85,    // X
            6,    // Y (centrado vertical en header de 40)
            35,    // ancho
            10,    // alto (ajusta según el logo)
            '', '', '', 'T'
        );

        // Tarjeta principal (simula .containerFacture)
        $pdf->SetFillColor(249, 249, 249);
        $pdf->SetDrawColor(202, 202, 202);
        $pdf->RoundedRect(
            40, // X
            30, // Y
            125, // Ancho
            $heigth, // Alto
            8, // Radio de las esquinas
            '1234', // Estilo de las esquinas (1234 = todas las esquinas redondeadas)
            'DF' // 'DF' = dibujar y rellenar
        );
        // Ícono de transacción (simula .transaction img)
        $pdf->Image($iconUrl, 95, 40, 20, 20, '', '', '', false, 300, '', false, false, 0);

        // Mensaje principal (simula .h1Facture)
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->SetTextColor($iconColor[0], $iconColor[1], $iconColor[2]);
        $pdf->SetXY(35, 65);
        $pdf->Cell(
            140, // Ancho total de la tarjeta
            10, // Alto de la celda
            $message,// Texto a mostrar
            0, // Borde (0 = sin borde)
            12, // Salto de línea (2 = salto de línea después de la celda)
            'C', // Alineación (C = centrado)
            false // Ajuste automático de ancho
        );

        // Referencia (simula h2)
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->SetTextColor($iconColor);
        $pdf->Cell(140, 8, $epayco_refecence, 0, 2, 'C', false);

        // Fecha
        $pdf->SetFont('dejavusans', '', 11);
        $pdf->Cell(140, 8, $x_transaction_date, 0, 2, 'C', false);

        // Subtítulo método de pago
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->Cell(0, 8, $paymentMethod, 0, 1, 'L', false);

        // Datos de método de pago (simula .parDescription)
        $pdf->SetFont('dejavusans', '', 10);


        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80); // Color gris oscuro
        $pdf->Cell(40, 5, $paymentMethod, 0, 0, 'L');
        $pdf->Cell(25, 5, '', 0, 0); // Celda vacía de 30mm de ancho como espacio
        $pdf->Cell(40, 5, $authorizations, 0, 1, 'L');
/*
        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 5, $franchise_logo. " ". $x_cardnumber_, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25); // Mueve el cursor 30mm a la derecha
        $pdf->Cell(50, 5, $authorization, 0, 1, 'L');
*/
        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        // Imprime el logo de la franquicia en la posición actual
        //$pdf->Image($franchise_logo, $pdf->GetX(), $pdf->GetY(), 10, 5, '', '', '', false, 300, '', false, false, 0);
        $pdf->Image($franchise_logo, 95, 40, 20, 20, '', '', '', false, 300, '', false, false, 0);
        // Mueve el cursor a la derecha del logo (ajusta el valor 12 si el logo es más ancho)
        $pdf->SetX($pdf->GetX() + 12);
        // Imprime el número de tarjeta al lado del logo
        $pdf->Cell(28, 5, $x_cardnumber_, 0, 0, 'L');
        // Mueve el cursor a la derecha para la siguiente celda
        $pdf->SetX($pdf->GetX() + 25);
        // Imprime la autorización
        $pdf->Cell(50, 5, $authorization, 0, 1, 'L');



        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 5, $receipt, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 5, $iPaddress, 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, $factura, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, $ip, 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 6, $response, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        if (!$is_cash) {
            $pdf->Cell(50, 6, '', 0, 1, 'L');
        }else{
            $pdf->Cell(50, 6, $expirationDateText, 0, 1, 'L');
        }

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, $response_reason_text, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        if (!$is_cash){
            $pdf->Cell(50, 6, '', 0, 1, 'L');
        }else{
            $pdf->Cell(50, 6, $expirationDate, 0, 1, 'L');


            $pdf->SetXY(55, $pdf->GetY()+5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(40, 6, $code, 0, 0, 'L');
            $pdf->SetX($pdf->GetX() + 25);
            $pdf->Cell(50, 6, 'Pin', 0, 1, 'L');

            $pdf->SetXY(55, $pdf->GetY());
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(40, 6, $codeProject, 0, 0, 'L');
            $pdf->SetX($pdf->GetX() + 25);
            $pdf->Cell(50, 6, $pin, 0, 1, 'L');

        }

        // Subtítulo detalles de la compra
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->Cell(0, 8, $purchase, 0, 1, 'L', false);

        // Detalles de la compra
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 6, $reference, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, $description, 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, $x_ref_payco, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, $description_, 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 6, $totalValue, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, 'Subtotal', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, $x_amount. " " .$x_currency_code, 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, $x_amount_base. " " .$x_currency_code, 0, 1, 'L');


        /*foreach ($data as $key => $value) {
            $pdf->Cell(50, 10, $key, 1, 0, 'L', true);
            $pdf->Cell(0, 10, $value, 1, 1, 'L', false);
        }
        */

        if (ob_get_length()) {
            ob_end_clean();
        }


        if (headers_sent()) {
            //throw new Exception('Error: Los encabezados ya fueron enviados.');
            exit;
        }


        $pdf->Output('Referencia-' . $x_ref_payco . '.pdf', 'D');
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