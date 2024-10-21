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
    private const MP_URL = 'https://www.mercadopago.com';

    /**
     * @const
     */
    private const MP_URL_PREFIX = 'https://www.mercadopago';

    /**
     * @const
     */
    private const EP_DASHBOARD = 'https://dashboard.epayco.io';

    /**
     * @const
     */
    private const MP_DEVELOPERS_URL = 'https://developers.mercadopago.com';

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
            $this->getDocumentationLinks($countryConfig),
            $this->getEpaycoLinks($countryConfig),
            $this->getCreditsLinks($countryConfig),
            $this->getAdminLinks(),
            $this->getStoreLinks(),
            $this->getWordpressLinks()
        );
    }

    /**
     * Get documentation links on Sdk Devsite page
     *
     * @param array $countryConfig
     *
     * @return array
     */
    private function getDocumentationLinks(array $countryConfig): array
    {
        $baseLink = self::MP_URL_PREFIX . $countryConfig['suffix_url'] . '/developers/' . $countryConfig['translate'];

        return [
            'docs_developers_program'       => $baseLink . '/developer-program',
            'docs_test_cards'               => $baseLink . '/docs/checkout-api/additional-content/your-integrations/test/cards',
            'docs_integration_credentials'  => 'https://dashboard.epayco.com/configuration',
            'docs_reasons_refusals'         => $baseLink . '/docs/woocommerce/reasons-refusals',
            'docs_ipn_notification'         => $baseLink . '/docs/woocommerce/integration-configuration/notifications',
            'docs_integration_test'         => $baseLink . '/docs/woocommerce/integration-test',
            'docs_integration_config'       => $baseLink . '/docs/woocommerce/integration-configuration',
            'reasons_refusals'              => $baseLink . '/docs/woocommerce/reasons-refusals',
            'docs_support_faq'              => $baseLink . '/support/26097',
        ];
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
            'epayco_home'                 => self::MP_URL_PREFIX . $countryConfig['suffix_url'] . '/home',
            'epayco_costs'                => self::MP_URL_PREFIX . $countryConfig['suffix_url'] . '/costs-section',
            'epayco_test_user'            => self::MP_URL . '/developers/panel/test-users',
            'epayco_credentials'          => self::EP_DASHBOARD .  '/configuration',
            'epayco_developers'           => self::MP_DEVELOPERS_URL,
            'epayco_pix'                  => self::MP_URL_PREFIX . '.com.br/ferramentas-para-vender/aceitar-pix',
            'epayco_debts'                => self::MP_URL_PREFIX . '.com.ar/cuotas',
            'epayco_support'              => self::MP_URL_PREFIX . $countryConfig['suffix_url'] . '/developers/' . $countryConfig['translate'] . '/support/contact?utm_source=CPWOOCOMMERCE',
            'epayco_terms_and_conditions' => self::MP_URL_PREFIX . $countryConfig['suffix_url'] . $countryConfig['help'] . $countryConfig['terms_and_conditions'],
            'epayco_pix_config'           => self::MP_URL_PREFIX . '.com.br/stop/pix?url=https://www.epayco.com.br/admin-pix-keys/my-keys?authentication_mode=required',
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
     * Get store links
     *
     * @param array $countryConfig
     *
     * @return array
     */
    private function getCreditsLinks(array $countryConfig): array
    {
        $siteId = $countryConfig['site_id'];

        $country_links = [
            'MLA' => [
                'credits_blog_link' => 'https://vendedores.epayco.com.ar/nota/impulsa-tus-ventas-y-alcanza-mas-publico-con-mercado-credito',
                'credits_faq_link'  => 'https://www.epayco.com.ar/help/19040'
            ],
            'MLM' => [
                'credits_blog_link' => 'https://vendedores.epayco.com.mx/nota/impulsa-tus-ventas-y-alcanza-a-mas-clientes-con-mercado-credito',
                'credits_faq_link'  => 'https://www.epayco.com.mx/help/19040'
            ],
            'MLB' => [
                'credits_blog_link' => 'https://conteudo.epayco.com.br/parcelamento-via-boleto-bancario-no-mercado-pago-seus-clientes-ja-podem-solicitar',
                'credits_faq_link'  => 'https://www.epayco.com.br/help/19040'
            ],
        ];

        return array_key_exists($siteId, $country_links) ? $country_links[$siteId] : $country_links['MLA'];
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
