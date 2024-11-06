<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

/**
 * Subscriptions methods
 */

class Subscriptions extends Resource
{
    /**
     * Create subscription
     * @param  object $options data client and plan
     * @param $type String
     * @return object
     */
    public function create($options = null,$type = "basic")
    {

        $url = $type == "basic" ? "/recurring/v1/subscription/create" : "/recurring/v1/subscription/domiciliacion/create";

        return $this->request(
            "POST",
            $url,
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }


    /**
     * Update subscription
     * @param  String $id     id subscription
     * @param  object $options contenten update
     * @return object
     */
    public function update($id, $options = null,$type = "basic")
    {
        switch ($type){
            case "basic":
                //TODO: update basic subscription in development
                return null;
                break;
            case "domiciliacion":
                return $this->request(
                    "POST",
                    "recurring/v1/subscription/domiciliacion/edit/" . $id . "/" . $this->epayco->api_key,
                    $this->epayco->api_key,
                    $options,
                    $this->epayco->private_key,
                    $this->epayco->test,
                    false,
                    $this->epayco->lang
                );
                break;
        }
    }

    /**
     * Get subscription from id
     * @param  String $uid id subscription
     * @return object
     */
    public function get($uid)
    {
        return $this->request(
            "GET",
            "/recurring/v1/subscription/" . $uid . "/" . $this->epayco->api_key  . "/",
            $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Get all subscriptions from client epayco
     * @return object
     */
    public function getList()
    {
        return $this->request(
            "GET",
            "/recurring/v1/subscriptions/" . $this->epayco->api_key,
            $this->epayco->api_key,
            null,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Cancel active subscription
     * @param  String $uid id subscription
     * @return object
     */
    public function cancel($uid)
    {
        return $this->request(
            "POST",
            "/recurring/v1/subscription/cancel",
            $this->epayco->api_key,
            ["id" => $uid],
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * Create subscription
     * @param  object $options data client and plan
     * @return object
     */
    public function charge($options = null)
    {
        return $this->request(
            "POST",
            "/payment/v1/charge/subscription/create",
            $this->epayco->api_key,
            $options,
            $this->epayco->private_key,
            $this->epayco->test,
            false,
            $this->epayco->lang
        );
    }

    /**
     * graphql query client epayco
     * @return object
     */
    public function query($query,$type,$custom_key = null){

        //return $this->graphql($query,'subscription',$this->epayco->api_key,$type,$custom_key);
    }
}

