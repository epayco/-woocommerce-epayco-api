<?php

namespace Epayco\Woocommerce\Sdk\Common;

/**
 * Class Config
 *
 * @package Epayco\Woocommerce\Sdk\Common
 */
class Config
{
    /**
     * @var string
     */
    private $access_token;

    /**
     * @var string
     */
    private $public_key;
    /**
     * @var string
     */
    private $private_key;

    /**
     * @var string
     */
    private $p_cust_id;

    /**
     * @var string
     */
    private $p_key;



    /**
     * Config constructor.
     *
     * @param string|null $access_token
     * @param string|null $public_key
     * @param string|null $private_key
     * @param string|null $p_cust_id
     * @param string|null $p_key
     */
    public function __construct(
        string $access_token = null,
        string $public_key = null,
        string $private_key = null,
        string $p_cust_id = null,
        string $p_key = null
    ) {
        $this->access_token = $access_token;
        $this->public_key = $public_key;
        $this->private_key = $private_key;
        $this->p_cust_id = $p_cust_id;
        $this->p_key = $p_key;

    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->{$name};
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set(string $name, string $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }
    }
}
