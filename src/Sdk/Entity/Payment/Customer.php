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
     * Get client for id
     * @param  String $uid id client
     * @return object
     */
    public function get($uid)
    {
        return $this->request(
            "GET",
            "/payment/v1/customer/" . $this->epayco->api_key . "/" . $uid . "/",
             $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Get list customer from client epayco
     * @return object
     */
    public function getList()
    {
        return $this->request(
            "GET",
            "/payment/v1/customers/" . $this->epayco->api_key . "/",
            $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Update customer from client epayco
     * @return object
     */
    public function update($uid, $options = null)
    {
        return $this->request(
            "POST",
            "/payment/v1/customer/edit/" . $this->epayco->api_key . "/" . $uid . "/",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * delete customer from client epayco
     * @return object
     */
    public function delete($options = null)
    {
        return $this->request(
            "POST",
            "/v1/remove/token",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }


    /**
     * add default card
     * @return object
     */
    public function addDefaultCard($options = null)
    {
        return $this->request(
            "POST",
            "/payment/v1/customer/reasign/card/default",
            $api_key = $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang,
            false,
            true
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
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang,
            false,
            true
        );
    }
}