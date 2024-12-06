<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

/**
 * Class BankI
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
Class Bank extends Resource
{
    public function pseBank($testMode = null)
    {
        $url = "/pse/bancos.json?public_key=" . $this->epayco->api_key;
        if(isset($testMode) && gettype($testMode) === "boolean"){
            $test = $testMode  ? "TRUE" : "FALSE";
            $url = $url."&test=".$test;
        }
        return $this->request(
            "GET",
            $url,
            $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            true,
            $this->epayco->lang,
            null,
            null,
            false
        );
    }

    public function create($options = null)
    {
        return $this->request(
            "POST",
            "/payment/process/pse",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang,
            null,
            null,
            true
        );
    }
}