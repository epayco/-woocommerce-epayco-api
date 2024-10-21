<?php

namespace Epayco\Woocommerce\Sdk\HttpClient;

use Epayco\Woocommerce\Sdk\Common\AbstractCollection;
use Epayco\Woocommerce\Sdk\Common\AbstractEntity;

/**
 * Class Response
 *
 * @package Epayco\Woocommerce\Sdk\HttpClient
 */
class Response
{
    /**
     * Response status
     *
     * @var int
     **/
    private $status;

    /**
     * Response data
     *
     * @var AbstractEntity|AbstractCollection
     **/
    private $data;

    /**
     * Response data
     *
     * @var array
     **/
    private $headers;

    /**
     * Response constructor.
     */
    public function __construct()
    {
    }

    /**
     * Return ths status of response
     *
     * @return int
     **/
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set the status of response
     *
     * @param int|null $status
     *
     * @return void
     **/
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * Return the data of response
     *
     * @return object|null
     **/
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the data of response
     *
     * @param object $data
     *
     * @return void
     **/
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Return the data of response
     *
     * @return array
     **/
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the data of response
     *
     * @param array $headers
     *
     * @return void
     **/
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
}
