<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

/**
 * Class Customer
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
Class Charge extends Resource{
    /**
     * Create charge
     * @param  object $options data charge
     * @param boolean $discount
     * @return object
     */
    public function create($options = null,$discount = false)
    {
        $url = $discount == true ? "/payment/v1/charge/discount/create" : "/payment/v1/charge/create";
        //$url = "/payment/process";
        return $this->request(
            "POST",
            $url,
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang,
            false,
            false,
            false
        );
    }
}