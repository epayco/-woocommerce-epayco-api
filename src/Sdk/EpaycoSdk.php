<?php

namespace Epayco\Woocommerce\Sdk;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;
use Epayco\Woocommerce\Sdk\Common\Config;
use Epayco\Woocommerce\Sdk\Common\Manager;
use Epayco\Woocommerce\Sdk\Entity\Payment\Payment;
use Epayco\Woocommerce\Sdk\Entity\Payment\Bank;
use Epayco\Woocommerce\Sdk\Entity\Payment\Cash;
use Epayco\Woocommerce\Sdk\Entity\Payment\Charge;
use Epayco\Woocommerce\Sdk\Entity\Payment\Customer;
use Epayco\Woocommerce\Sdk\Entity\Payment\Daviplata;
use Epayco\Woocommerce\Sdk\Entity\Payment\Transaction;
use Epayco\Woocommerce\Sdk\HttpClient\HttpClient;
use Epayco\Woocommerce\Sdk\HttpClient\Requester\CurlRequester;
use Epayco\Woocommerce\Sdk\HttpClient\Requester\RequesterInterface;

class EpaycoSdk
{

    public static $cache = [];
    //public const  BASEURL = "https://apify.epayco.co";
    public const  BASEURL = "https://apify.epayco.io";
    /**
     * Public key client
     * @var String
     */
    public $api_key;
    /**
     * Private key client
     * @var String
     */
    public $private_key;

    /**
     * test mode transaction
     * @var String
     */
    public $test;

    /**
     * lang client errors
     * @var String
     */
    public $lang;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var RequesterInterface
     */
    private $requester;

    /**
     * @param String $access_token
     * @param String $platform_id
     * @param String $product_id
     * @param String $integrator_id
     * @param String $public_key
     */
    /**
     * Constructor methods publics
     * @param array $options
     */
    public function __construct(
        $options,
        string $access_token = null,
        string $public_key = null,
        string $private_key = null,
        string $p_cust_id = null,
        string $p_key = null
    )
    {
        $this->api_key = $options["apiKey"];
        $this->private_key = $options["privateKey"];
        $this->test = $options["test"] ? "TRUE" : "FALSE";
        $this->lang = $options["lenguage"];
        $this->requester = new CurlRequester();
        $this->config = new Config();
        $parameters = [
            'access_token' => $access_token,
            'public_key' => $public_key,
            'private_key' => $private_key,
            'p_cust_id' => $p_cust_id,
            'p_key' => $p_key
        ];

        foreach ($parameters as $key => $value) {
            if (!empty($value) && isset($value)) {
                $this->config->__set($key, $value);
            }
        }

        $this->bank = new Bank($this);
        $this->cash = new Cash($this);
        $this->charge = new Charge($this);
        $this->customer = new Customer($this);
        $this->daviplata = new Daviplata($this);
        $this->transaction = new Transaction($this);
    }



    /**
     * @param string $entityName
     * @param string $baseUrl
     *
     * @return AbstractEntity
     */
    public function getEntityInstance(string $entityName, string $baseUrl)
    {
        $client  = new HttpClient($baseUrl, $this->requester);
        $manager = new Manager($client, $this->config);
        return new $entityName($manager);
    }

    /**
     * @return Payment
     */
    public function getPaymentInstance()
    {
        return $this->getEntityInstance('Epayco\Woocommerce\Sdk\Entity\Payment\Payment', self::BASEURL);
    }
}