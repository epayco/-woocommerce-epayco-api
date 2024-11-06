<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

/**
 * Class Customer
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
Class Plan extends Resource{
    /**
     * Create plan
     * @param  object $options data from plan
     * @return object
     */
    public function create($options = null)
    {
        return $this->request(
            "POST",
            "/recurring/v1/plan/create",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Get plan from id
     * @param   $uid id plan
     * @return object
     */
    public function get($uid)
    {
        return $this->request(
            "GET",
            "/recurring/v1/plan/" . $this->epayco->api_key . "/" . $uid . "/",
            $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Get list all plans from client epayco
     * @return object
     */
    public function getList()
    {
        return $this->request(
            "GET",
            "/recurring/v1/plans/" . $this->epayco->api_key,
            $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Update plan
     * @param  String $uid     id plan
     * @param  object $options contenten update
     * @return object
     */
    public function update($uid, $options = null)
    {
        return $this->request(
            "POST",
            "/recurring/v1/plan/edit/" . $this->epayco->api_key . "/" . $uid . "/",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * remove plan
     * @param  String $uid     id plan
     * @param  object $options contenten update
     * @return object
     */
    public function remove($uid, $options = null)
    {
        return $this->request(
            "POST",
            "/recurring/v1/plan/remove/" . $this->epayco->api_key . "/" . $uid . "/",
            $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }
}