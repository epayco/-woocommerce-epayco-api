<?php

namespace Epayco\Woocommerce\Sdk\HttpClient\Requester;

use Epayco\Woocommerce\Sdk\Common\AbstractCollection;
use Epayco\Woocommerce\Sdk\Common\AbstractEntity;
use Epayco\Woocommerce\Sdk\HttpClient\Response;

/**
 * Interface RequesterInterface
 *
 * @package Epayco\Woocommerce\Sdk\HttpClient\Requester
 */
interface RequesterInterface
{
    /**
     * @param string|AbstractEntity|AbstractCollection|null $body
     *
     * @return resource
     */
    public function createRequest(string $method, string $uri, array $headers = [], $body = null);

    /**
     * @param resource $request
     */
    public function sendRequest($request): Response;
}
