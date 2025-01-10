<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;
use WpOrg\Requests\Requests;
class Client
{
    const BASE_URL = "https://api.secure.epayco.io";
    const BASE_URL_SECURE = "https://secure2.epayco.io/restpagos";
    const BASE_URL_APIFY = "https://apify.epayco.io";
    const IV = "0000000000000000";
    const LENGUAGE = "php";

    /**
     * Request api epayco
     * @param String $method method petition
     * @param String $url url request api epayco
     * @param String $api_key public key commerce
     * @param Object $data data petition
     * @param String $private_key private key commerce
     * @param String $test type petition production or testing
     * @param Boolean $switch type api petition
     * @return false|Object|string
     */
    public function request(
        $method,
        $url,
        $api_key,
        $data,
        $private_key,
        $test,
        $switch,
        $lang,
        $cash = null,
        $card = null,
        $apify = false
    )
    {

        try {
            $util = new Util();
            $cookie_name = $api_key . ($apify ? "_apify" : "");
            if(!isset($_COOKIE[$cookie_name])) {
                //  echo "Cookie named '" . $cookie_name . "' is not set!";
                $dataAuth =$this->authentication($api_key,$private_key, $apify);
                $json = json_decode($dataAuth);
                if(!is_object($json)) {
                    //throw new Exception("Error get bearer_token.", 106);
                }
                $bearer_token = false;
                if(isset($json->bearer_token)) {
                    $bearer_token=$json->bearer_token;
                }else if(isset($json->token)){
                    $bearer_token= $json->token;
                }
                if(!$bearer_token) {
                    $msj = isset($json->message) ? $json->message : "Error get bearer_token";
                    if($msj == "Error get bearer_token" && isset($json->error)){
                        $msj = $json->error;
                    }
                   // throw new ErrorException($msj, 422);
                }
                $cookie_value = $bearer_token;
                setcookie($cookie_name, $cookie_value, time() + (60 * 14), "/");
                //  echo "token con login".$bearer_token;
            }else{
                $bearer_token = $_COOKIE[$cookie_name];
            }
            /**
             * Set headers
             */
            $headers = array("Content-Type" => "application/json", "Accept" => "application/json", "Type" => 'sdk-jwt', "Authorization" => 'Bearer ' . $bearer_token, "lang" => "PHP");

            $options = array(
                'timeout' => 120,
                'connect_timeout' => 120,
            );
            if ($method == "GET") {
                if ($apify) {
                    $_url = $this->getEpaycoBaseApify(Client::BASE_URL_APIFY) . $url;
                } elseif ($switch) {
                    $_url = $this->getEpaycoSecureBaseUrl(Client::BASE_URL_SECURE) . $url;
                } else {
                    $_url = $this->getEpaycoBaseUrl(Client::BASE_URL) . $url;
                }
                $response = Requests::get($_url, $headers, $options);
            } elseif ($method == "POST") {
                if($apify){
                    $response = Requests::post($this->getEpaycoBaseApify(Client::BASE_URL_APIFY) . $url, $headers, json_encode($data), $options);
                }
                elseif ($switch) {
                    $data = $util->mergeSet($data, $test, $lang, $private_key, $api_key, $cash);

                    $response = Requests::post($this->getEpaycoSecureBaseUrl(Client::BASE_URL_SECURE) . $url, $headers, json_encode($data), $options);
                } else {

                    if (!$card) {
                        $data["ip"] = isset($data["ip"]) ? $data["ip"] : getHostByName(getHostName());
                        $data["test"] = $test;
                    }
                    $response = Requests::post($this->getEpaycoBaseUrl(Client::BASE_URL) . $url, $headers, json_encode($data), $options);

                }
            } elseif ($method == "DELETE") {
                $response = Requests::delete($this->getEpaycoBaseUrl(Client::BASE_URL) . $url, $headers, $options);
            }


            if ($response->status_code >= 200 && $response->status_code <= 206) {
                if ($method == "DELETE") {
                    return $response->status_code == 204 || $response->status_code == 200;
                }
                return json_decode($response->body);
            }
            if ($response->status_code == 400) {
                try {
                    $error = (array)json_decode($response->body)->errors[0];
                    $message = current($error);
                } catch (\Exception $e) {
                    //throw new ErrorException($e->getMessage(), $e->getCode());
                }
                //throw new ErrorException($message, 103);
            }
            if ($response->status_code == 401) {
                //throw new ErrorException('Unauthorized', 104);
            }
            if ($response->status_code == 404) {
                //throw new ErrorException('Not found', 105);
            }
            if ($response->status_code == 403) {
                //throw new ErrorException('Permission denegated', 106);
            }
            if ($response->status_code == 405) {
                //throw new ErrorException('Not allowed', 107);
            }

        }catch (\Exception $e) {
            $data = array(
                "status" => false,
                "message" => $e->getMessage(),
                "data" => array()
            );
            $objectReturnError = (object)$data;
            return $objectReturnError;
        }
    }

    public function authentication($api_key, $private_key, $apify)
    {
        $data = array(
            'public_key' => $api_key,
            'private_key' => $private_key
        );
        $headers = array("Content-Type" => "application/json", "Accept" => "application/json", "Type" => 'sdk-jwt', "lang" => "PHP");

        $options = array(
            'timeout' => 120,
            'connect_timeout' => 120,
        );

        if($apify){
            $token = base64_encode($api_key.":".$private_key);
            $headers["Authorization"] = "Basic ".$token;
            $data = [];
        }
        $url = $apify ? $this->getEpaycoBaseApify(Client::BASE_URL_APIFY). "/login" : $this->getEpaycoBaseUrl(Client::BASE_URL)."/v1/auth/login";
        $response = Requests::post($url, $headers, json_encode($data), $options);
        return isset($response->body) ? $response->body : false;
    }

    protected function getEpaycoSecureBaseUrl($default)
    {
        $epaycoEnv = getenv('EPAYCO_PHP_SDK_ENV_REST');

        if (false === $epaycoEnv || 'prod' === $epaycoEnv) {
            return $default;
        } else if ($epaycoEnv) {
            return getenv('EPAYCO_PHP_SDK_ENV_REST');
        }

        return $default;
    }

    protected function getEpaycoBaseApify($default)
    {
        $epaycoEnv = getenv('BASE_URL_APIFY');
        if($epaycoEnv){
            return $epaycoEnv;
        }
        return $default;
    }

    protected function getEpaycoBaseUrl($default)
    {
        $epaycoEnv = getenv('EPAYCO_PHP_SDK_ENV');
        if($epaycoEnv){
            return $epaycoEnv;
        }
        return $default;
    }


}