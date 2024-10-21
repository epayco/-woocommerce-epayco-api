<?php

namespace Epayco\Woocommerce\Hooks;

use Epayco\Woocommerce\Helpers\Country;
use Epayco\Woocommerce\Helpers\Url;
use Epayco\Woocommerce\Configs\Seller;
use Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils;

if (!defined('ABSPATH')) {
    exit;
}

class Scripts
{
    /**
     * @const
     */
    private const SUFFIX = '_params';

    /**
     * @const
     */
    private const NOTICES_SCRIPT_NAME = 'wc_epayco_notices';

    /**
     * @var Url
     */
    private $url;

    /**
     * @var Seller
     */
    private $seller;

    /**
     * Scripts constructor
     *
     * @param Url $url
     * @param Seller $seller
     */
    public function __construct(Url $url, Seller $seller)
    {
        $this->url    = $url;
        $this->seller = $seller;
    }

    /**
     * Register styles on admin
     *
     * @param string $name
     * @param string $file
     *
     * @return void
     */
    public function registerAdminStyle(string $name, string $file): void
    {
        add_action('admin_enqueue_scripts', function () use ($name, $file) {
            $this->registerStyle($name, $file);
        });
    }

    /**
     * Register scripts on admin
     *
     * @param string $name
     * @param string $file
     * @param array $variables
     *
     * @return void
     */
    public function registerAdminScript(string $name, string $file, array $variables = []): void
    {
        add_action('admin_enqueue_scripts', function () use ($name, $file, $variables) {
            $this->registerScript($name, $file, $variables);
        });
    }

    /**
     * Register styles on checkout
     *
     * @param string $name
     * @param string $file
     *
     * @return void
     */
    public function registerCheckoutStyle(string $name, string $file): void
    {
        add_action('wp_enqueue_scripts', function () use ($name, $file) {
            $this->registerStyle($name, $file);
        });
    }

    /**
     * Register scripts on checkout
     *
     * @param string $name
     * @param string $file
     * @param array $variables
     *
     * @return void
     */
    public function registerCheckoutScript(string $name, string $file, array $variables = []): void
    {
        add_action('wp_enqueue_scripts', function () use ($name, $file, $variables) {
            $this->registerScript($name, $file, $variables);
        });
    }

    /**
     * Register styles on store
     *
     * @param string $name
     * @param string $file
     *
     * @return void
     */
    public function registerStoreStyle(string $name, string $file): void
    {
        $this->registerStyle($name, $file);
    }

    /**
     * Register scripts on store
     *
     * @param string $name
     * @param string $file
     * @param array $variables
     *
     * @return void
     */
    public function registerStoreScript(string $name, string $file, array $variables = []): void
    {
        $this->registerScript($name, $file, $variables);
    }

    /**
     * Register notices script on admin
     *
     * @return void
     */
    public function registerNoticesAdminScript(): void
    {
        global $woocommerce;

        $file      = $this->url->getPluginFileUrl('assets/js/notices/notices-client', '.js');
        $variables = [
            'site_id'          => $this->seller->getSiteId() ?: Country::SITE_ID_MLA,
            'container'        => '#wpbody-content',
            'public_key'       => $this->seller->getCredentialsPublicKeyPayment(),
            'plugin_version'   => EP_VERSION,
            'platform_id'      => EP_PLATFORM_ID,
            'platform_version' => $woocommerce->version,
        ];

        $this->registerAdminScript(self::NOTICES_SCRIPT_NAME, $file, $variables);
    }

    /**
     * Register credits script on admin
     *
     * @param string $name
     * @param string $file
     * @param array $variables
     *
     * @return void
     */
    public function registerCreditsAdminScript(string $name, string $file, array $variables = []): void
    {
        if ($this->url->validateSection('woo-epayco-credits')) {
            $this->registerAdminScript($name, $file, $variables);
        }
    }

    /**
     * Register credits style on admin
     *
     * @param string $name
     * @param string $file
     *
     * @return void
     */
    public function registerCreditsAdminStyle(string $name, string $file): void
    {
        if ($this->url->validateSection('woo-epayco-credits')) {
            $this->registerAdminStyle($name, $file);
        }
    }





    /**
     * Register scripts for payment block
     *
     * @param string $name
     * @param string $file
     * @param string $version
     * @param array $deps
     * @param array $variables
     *
     * @return void
     */
    public function registerPaymentBlockScript(string $name, string $file, string $version, array $deps = [], array $variables = []): void
    {
        wp_register_script($name, $file, $deps, $version, true);
        if ($variables) {
            wp_localize_script($name, $name . self::SUFFIX, $variables);
        }
    }

    /**
     * Register styles
     *
     * @param string $name
     * @param string $file
     *
     * @return void
     */
    private function registerStyle(string $name, string $file): void
    {
        wp_register_style($name, $file, false, EP_VERSION);
        wp_enqueue_style($name);
    }

    /**
     * Register scripts
     *
     * @param string $name
     * @param string $file
     * @param array $variables
     *
     * @return void
     */
    private function registerScript(string $name, string $file, array $variables = []): void
    {
        wp_enqueue_script($name, $file, [], EP_VERSION, true);

        if ($variables) {
            wp_localize_script($name, $name . self::SUFFIX, $variables);
        }
    }
}
