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

    public function download($ref_payco)
    {
        $data = array(
            "filter" => array("referencePayco" => $ref_payco),
            "success" =>true
        );
        $epaycoSdk = $this->getSdkInstance();
        $transactionDetails = $epaycoSdk->transaction->get($data,true,"POST");
        $transactionInfo = json_decode(wp_json_encode($transactionDetails), true);

        if (empty($transactionInfo)) {
            return;
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
        switch ($x_response) {
            case 'Aceptada': {
                $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('check.png');
                $iconColor = [103, 201, 64];
                //$message = $payment->storeTranslations['success_message'];
            }break;
            case 'Pendiente':
            case 'Pending':{
                $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('warning.png');
                $iconColor = [255, 209, 0];
                //$message = $payment->storeTranslations['pending_message'];
            }break;
            default: {
                $iconUrl = $this->epayco->hooks->gateway->getGatewayIcon('error.png');
                $iconColor = [225, 37, 27];
                //$message = $payment->storeTranslations['fail_message'];
            }break;
        }
        $is_cash = false;
        if($x_franchise == 'EF'||
            $x_franchise == 'GA'||
            $x_franchise == 'PR'||
            $x_franchise == 'RS'||
            $x_franchise == 'SR'
        ){
            $x_cardnumber_ = null;
            $is_cash = true;
        }else{
            if($x_franchise == 'PSE' || $x_franchise == 'DP' || $x_franchise == 'DaviPlata' ){
                $x_cardnumber_ = null;
            }else{
                $x_cardnumber_ = isset($x_cardnumber)?substr($x_cardnumber, -8):null;
            }
            $x_franchise = $x_franchise == 'DaviPlata' ? 'DP' : $x_franchise;
        }
        $data= [
            'franchise_logo' => 'https://secure.epayco.co/img/methods/'.$x_franchise.'.svg',
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
            //'message' => $message,
            //'error_message' => $payment->storeTranslations['error_message'],
            //'error_description' => $payment->storeTranslations['error_description'],
            //'payment_method'  => $payment->storeTranslations['payment_method'],
            //'response'=> $payment->storeTranslations['response'],
            //'dateandtime' => $payment->storeTranslations['dateandtime'],
            'authorization' => $x_approval_code,
            'iconUrl' => $iconUrl,
            'iconColor' => $iconColor,
            'epayco_icon' => $this->epayco->hooks->gateway->getGatewayIcon('logo_white.png'),
            'ip' => $x_customer_ip,
            //'totalValue' => $payment->storeTranslations['totalValue'],
            //'description' => $payment->storeTranslations['description'],
            //'reference' => $payment->storeTranslations['reference'],
            //'purchase' => $payment->storeTranslations['purchase'],
            //'iPaddress' => $payment->storeTranslations['iPaddress'],
            /*'receipt' => $payment->storeTranslations['receipt'],
            'authorizations' => $payment->storeTranslations['authorization'],
            'paymentMethod'  => $payment->storeTranslations['paymentMethod'],
            'epayco_refecence'  => $payment->storeTranslations['epayco_refecence'],
            'donwload_text' => $payment->storeTranslations['donwload_text'],
            'code' => $payment->storeTranslations['code']??null,*/
            'is_cash' => $is_cash
        ];
        $pdf = new TCPDF();
        //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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


        $titulo = 'Transacción ' . ucfirst(strtolower($x_transaction_state));

        // Tarjeta principal (simula .containerFacture)
        $pdf->SetFillColor(249, 249, 249);
        $pdf->SetDrawColor(202, 202, 202);
        $pdf->RoundedRect(
            40, // X
            30, // Y
            125, // Ancho
            180, // Alto
            8, // Radio de las esquinas
            '1234', // Estilo de las esquinas (1234 = todas las esquinas redondeadas)
            'DF' // 'DF' = dibujar y rellenar
        );
        // Ícono de transacción (simula .transaction img)
        $pdf->Image($iconUrl, 95, 40, 20, 20, '', '', '', false, 300, '', false, false, 0);

        // Mensaje principal (simula .h1Facture)
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->SetTextColor(255, 209, 0);
        $pdf->SetXY(35, 65);
        $pdf->Cell(
            140, // Ancho total de la tarjeta
            10, // Alto de la celda
            $titulo,// Texto a mostrar
            0, // Borde (0 = sin borde)
            12, // Salto de línea (2 = salto de línea después de la celda)
            'C', // Alineación (C = centrado)
            false // Ajuste automático de ancho
        );

        // Referencia (simula h2)
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->SetTextColor($iconColor);
        $pdf->Cell(140, 8, 'Referencia ePayco: ' . $x_ref_payco, 0, 2, 'C', false);

        // Fecha
        $pdf->SetFont('dejavusans', '', 11);
        $pdf->Cell(140, 8, $x_transaction_date, 0, 2, 'C', false);

        // Subtítulo método de pago
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->Cell(0, 8, 'Método de pago', 0, 1, 'L', false);

        // Datos de método de pago (simula .parDescription)
        $pdf->SetFont('dejavusans', '', 10);


        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80); // Color gris oscuro
        $pdf->Cell(40, 5, 'Método de pago', 0, 0, 'L');
        $pdf->Cell(25, 5, '', 0, 0); // Celda vacía de 30mm de ancho como espacio
        $pdf->Cell(40, 5, 'Autorización', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 5, 'Visa', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25); // Mueve el cursor 30mm a la derecha
        $pdf->Cell(50, 5, '000000', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 5, 'Recibo', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 5, 'Dirección IP', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, '144_test', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, '186.97.212.162', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 6, 'Respuesta', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, '', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, 'Transacción aprobada', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, '', 0, 1, 'L');


        // Subtítulo detalles de la compra
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->Cell(0, 8, 'Detalles de la compra', 0, 1, 'L', false);

        // Detalles de la compra
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 6, 'Referencia', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, 'Descripción', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, '287544283', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, 'test 01', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY()+5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(40, 6, 'Valor total', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, 'Subtotal', 0, 1, 'L');

        $pdf->SetXY(55, $pdf->GetY());
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40, 6, '$20000 COP', 0, 0, 'L');
        $pdf->SetX($pdf->GetX() + 25);
        $pdf->Cell(50, 6, '$20000 COP', 0, 1, 'L');


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
}