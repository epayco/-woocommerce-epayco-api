<?php

namespace Epayco\Woocommerce\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

final class Links
{
    /**
     * @const
     */
    private const EP_URL = 'https://www.epayco.com';

    /**
     * @const
     */
    private const EP_URL_PREFIX = 'https://www.epayco';

    /**
     * @const
     */
    private const EP_DASHBOARD = 'https://dashboard.epayco.io';


    /**
     * @var Country
     */
    private $country;

    /**
     * @var Url
     */
    private $url;

    /**
     * Links constructor
     *
     * @param Country $country
     * @param Url $url
     */
    public function __construct(Country $country, Url $url)
    {
        $this->country = $country;
        $this->url     = $url;
    }

    /**
     * Get all links
     *
     * @return array
     */
    public function getLinks(): array
    {
        $countryConfig = $this->country->getCountryConfigs();

        return array_merge_recursive(
            $this->getEpaycoLinks($countryConfig),
            $this->getAdminLinks(),
            $this->getStoreLinks(),
            $this->getWordpressLinks()
        );
    }



    /**
     * Get documentation links on Sdk Panel page
     *
     * @param array $countryConfig
     *
     * @return array
     */
    private function getEpaycoLinks(array $countryConfig): array
    {
        return [
            'epayco_home'                 => self::EP_URL,
            'epayco_credentials'          => self::EP_DASHBOARD .  '/configuration',
            'epayco_developers'           => self::EP_URL . '/desarrolladores/',
            'epayco_support'              => self::EP_URL . '/contacto/',
            'epayco_terms_and_conditions' => self::EP_URL. '/terminos-y-condiciones-epayco-vende/',
        ];
    }

    /**
     * Get admin links
     *
     * @return array
     */
    private function getAdminLinks(): array
    {
        return [
            'admin_settings_page' => admin_url('admin.php?page=epayco-settings'),
            'admin_gateways_list' => admin_url('admin.php?page=wc-settings&tab=checkout'),
        ];
    }

    /**
     * Get store links
     *
     * @return array
     */
    private function getStoreLinks(): array
    {
        return [
            'store_visit' => $this->url->getBaseUrl(),
        ];
    }



    /**
     * Get wordpress links
     *
     * @return array
     */
    private function getWordpressLinks(): array
    {
        return [
            'wordpress_review_link' => 'https://wordpress.org/support/plugin/woocommerce-epayco/reviews/?filter=5#new-post',
        ];
    }
}
