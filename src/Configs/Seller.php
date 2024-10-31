<?php

namespace Epayco\Woocommerce\Configs;

use Epayco\Woocommerce\Helpers\Cache;
use Epayco\Woocommerce\Hooks\Options;


if (!defined('ABSPATH')) {
    exit;
}

class Seller
{
    /**
     * @const
     */
    private const SITE_ID = '_site_id_v1';

    /**
     * @const
     */
    private const CLIENT_ID = '_ep_client_id';

    /**
     * @const
     */
    private const COLLECTOR_ID = '_collector_id_v1';

    /**
     * @const
     */
    private const CREDENTIALS_PUBLIC_KEY_PROD = '_ep_public_key_prod';

    /**
     * @const
     */
    private const CREDENTIALS_PUBLIC_KEY_TEST = '_ep_public_key_test';

    /**
     * @const
     */
    private const CREDENTIALS_ACCESS_TOKEN_PROD = '_ep_access_token_prod';

    /**
     * @const
     */
    private const CREDENTIALS_P_CUST_ID = '_ep_p_cust_id';

    /**
     * @const
     */
    private const CREDENTIALS_PUBLIC_KEY = '_ep_publicKey';

    /**
     * @const
     */
    private const CREDENTIALS_PRIVATE_KEY = '_ep_private_key';

    /**
     * @const
     */
    private const CREDENTIALS_P_KEY = '_ep_p_key';

    /**
     * @const
     */
    private const CREDENTIALS_ACCESS_TOKEN_TEST = '_ep_access_token_test';

    /**
     * @const
     */
    private const HOMOLOG_VALIDATE = 'homolog_validate';

    /**
     * @const
     */
    private const CHECKOUT_BASIC_PAYMENT_METHODS = '_checkout_payments_methods';

    /**
     * @const
     */
    private const CHECKOUT_TICKET_PAYMENT_METHODS = '_all_payment_methods_ticket';

    /**
     * @const
     */
    private const CHECKOUT_PSE_PAYMENT_METHODS = '_payment_methods_pse';

    /**
     * @const
     */
    private const SITE_ID_PAYMENT_METHODS = '_site_id_payment_methods';


    /**
     * @const
     */
    private const TEST_USER = '_test_user_v1';

    /**
     * @const
     */
    private const AUTO_UPDATE_PLUGINS = 'auto_update_plugins';

    /**
     * @const
     */
    private const EP_APIFY = 'https://apify.epayco.io';

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var Options
     */
    private $options;


    /**
     * @var Store
     */
    private $store;



    /**
     * Credentials constructor
     *
     * @param Cache $cache
     * @param Options $options
     * @param Store $store
     */
    public function __construct(Cache $cache, Options $options, Store $store)
    {
        $this->cache     = $cache;
        $this->options   = $options;
        $this->store     = $store;
    }

    /**
     * @return string
     */
    public function getSiteId(): string
    {
        return strtoupper($this->options->get(self::SITE_ID, ''));
    }

    /**
     * @param string $siteId
     */
    public function setSiteId(string $siteId): void
    {
        $this->options->set(self::SITE_ID, $siteId);
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->options->get(self::CLIENT_ID, '');
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->options->set(self::CLIENT_ID, $clientId);
    }

    /**
     * @return string
     */
    public function getCollectorId(): string
    {
        return $this->options->get(self::COLLECTOR_ID, '');
    }

    /**
     * @param string $collectorId
     */
    public function setCollectorId(string $collectorId): void
    {
        $this->options->set(self::COLLECTOR_ID, $collectorId);
    }




    /**
     * @return string
     */
    public function getCredentialsPCustId(): string
    {
        return $this->options->get(self::CREDENTIALS_P_CUST_ID, '');
    }

    /**
     * @param string $credentialsPcustId
     */
    public function setCredentialsPCustId(string $credentialsPcustId): void
    {
        $this->options->set(self::CREDENTIALS_P_CUST_ID, $credentialsPcustId);
    }

    /**
     * @return string
     */
    public function getCredentialsPkey(): string
    {
        return $this->options->get(self::CREDENTIALS_P_KEY, '');
    }

    /**
     * @param string $credentialsPKey
     */
    public function setCredentialsPkey(string $credentialsPKey): void
    {
        $this->options->set(self::CREDENTIALS_P_KEY, $credentialsPKey);
    }

    /**
     * @return string
     */
    public function getCredentialsPublicKeyPayment(): string
    {
        return $this->options->get(self::CREDENTIALS_PUBLIC_KEY, '');
    }

    /**
     * @param string $credentialsPublicKey
     */
    public function setCredentialsPublicKeyPayment(string $credentialsPublicKey): void
    {
        $this->options->set(self::CREDENTIALS_PUBLIC_KEY, $credentialsPublicKey);
    }

    /**
     * @return string
     */
    public function getCredentialsPrivateKeyPayment(): string
    {
        return $this->options->get(self::CREDENTIALS_PRIVATE_KEY, '');
    }

    /**
     * @param string $credentialsPrivateKey
     */
    public function setCredentialsPrivateKeyPayment(string $credentialsPrivateKey): void
    {
        $this->options->set(self::CREDENTIALS_PRIVATE_KEY, $credentialsPrivateKey);
    }






    /**
     * @return string
     */
    public function getCredentialsAccessTokenProd(): string
    {
        return $this->options->get(self::CREDENTIALS_ACCESS_TOKEN_PROD, '');
    }

    /**
     * @param string $credentialsAccessTokenProd
     */
    public function setCredentialsAccessTokenProd(string $credentialsAccessTokenProd): void
    {
        $this->options->set(self::CREDENTIALS_ACCESS_TOKEN_PROD, $credentialsAccessTokenProd);
    }

    /**
     * @return string
     */
    public function getCredentialsAccessTokenTest(): string
    {
        return $this->options->get(self::CREDENTIALS_ACCESS_TOKEN_TEST, '');
    }

    /**
     * @param string $credentialsAccessTokenTest
     */
    public function setCredentialsAccessTokenTest(string $credentialsAccessTokenTest): void
    {
        $this->options->set(self::CREDENTIALS_ACCESS_TOKEN_TEST, $credentialsAccessTokenTest);
    }

    /**
     * @return bool
     */
    public function getHomologValidate(): bool
    {
        return $this->options->get(self::HOMOLOG_VALIDATE);
    }

    /**
     * @param bool $homologValidate
     */
    public function setHomologValidate(bool $homologValidate): void
    {
        $this->options->set(self::HOMOLOG_VALIDATE, $homologValidate);
    }

    /**
     * @return bool
     */
    public function getTestUser(): bool
    {
        return $this->options->get(self::TEST_USER);
    }

    /**
     * @param bool $testUser
     */
    public function setTestUser(bool $testUser): void
    {
        $this->options->set(self::TEST_USER, $testUser);
    }

    /**
     * @return bool
     */
    public function isTestUser(): bool
    {
        return $this->getTestUser();
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return array
     */
    public function validatePublicKeyPayment(string $type, string $key): array
    {
        return $this->validateCredentialsPayment( $type, $key);
    }

    /**
     * @param string $publicKey
     * @param string $type
     *
     * @return array
     */
    public function validateEpaycoCredentials(string $publicKey, string $private_key): array
    {
        return $this->validateCredentialsPayment( $publicKey, $private_key, true);
    }

    /**
     * Validate credentials
     *
     * @param string|null $type
     * @param string|null $key
     *
     * @return array
     */
    private function validateCredentialsPayment( string $type = null, string $keys = null, bool $validate = false): array
    {
        try {
            if(!$validate){
                $key   = sprintf('%s%s',$type, $keys);
                $cache = $this->cache->getCache($key);
                if ($cache) {
                    return $cache;
                }
                $serializedResponse = [
                    'data'   => $key,
                    'status' => 200,
                ];
                $this->cache->setCache($key, $serializedResponse);
            }else{
                $serializedResponse = [
                    'data'   =>[],
                    'status' => false,
                ];
                $headers = [];
                $uri     = '/login';
                $accessToken = base64_encode($type.":".$keys);
                $headers[] = 'Authorization: Basic ' . $accessToken;
                $headers[] = 'Content-Type: application/json ';
                $body = array(
                    'public_key' => $type,
                    'private_key' => $keys,
                );
                $response           = $this->my_woocommerce_post_request($uri, $headers, $body);
                if(isset($response) && $response['token']){
                    $serializedResponse = [
                        'data'   => $response['token'],
                        'status' => true,
                    ];
                }

            }


            return $serializedResponse;
        } catch (\Exception $e) {
            return [
                'data'   => null,
                'status' => 500,
            ];
        }
    }

    private function my_woocommerce_post_request($uri, $headers, $body = []) {
        $url = self::EP_APIFY.$uri;
        /*$response = wp_remote_post( $url, array(
            'body'    => wp_json_encode( $body ),
            'headers' => $headers,
            'method'  => 'POST',
        ));*/
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $headers,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return "Something went wrong: $error_message";
        }
        //$response_body = wp_remote_retrieve_body( $response );
        $response_data = json_decode( $response, true );

        return $response_data;
    }



    /**
     * Get auto update mode
     *
     * @return bool
     */
    public function isAutoUpdate()
    {
        $auto_update_plugins = $this->options->get(self::AUTO_UPDATE_PLUGINS, '');

        if (is_array($auto_update_plugins) && in_array('-woocommerce-epayco-api/woocommerce-epayco.php', $auto_update_plugins)) {
            return true;
        }
        return false;
    }
}
