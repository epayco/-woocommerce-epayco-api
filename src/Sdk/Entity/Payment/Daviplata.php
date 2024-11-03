<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

/**
 * Class Customer
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
Class Daviplata extends Resource{
    /**
     * Return data payment cash
     * @param  array $options data transaction
     * @return object
     */
    public function create($options = null)
    {
        return $this->request(
            "POST",
            "/payment/process/daviplata",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang,
            true,
            false,
            true
        );
    }
}