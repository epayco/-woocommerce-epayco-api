<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

/**
 * Class Customer
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
Class Customer extends Resource{
    /**
     * Create client and asocciate credit card
     * @param  array $options client and token id info
     * @return object
     */
    public function create($options = null)
    {
        return $this->request(
            "POST",
            "/payment/v1/customer/create",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * add new token
     * @return object
     */
    public function addNewToken($options = null)
    {
        return $this->request(
            "POST",
            "/v1/customer/add/token",
            $api_key = $this->epayco->api_key,
            $options,
            $private_key = $this->epayco->private_key,
            $test = $this->epayco->test,
            $switch = false,
            $lang = $this->epayco->lang,
            $cash = false,
            $card = true
        );
    }
}