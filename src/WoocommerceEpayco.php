<?php

namespace Epayco\Woocommerce;

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Epayco\Woocommerce\Admin\Settings;
use Epayco\Woocommerce\Blocks\CheckoutBlock;
use Epayco\Woocommerce\Blocks\CreditCardBlock;
use Epayco\Woocommerce\Blocks\DaviplataBlock;
use Epayco\Woocommerce\Blocks\TicketBlock;
use Epayco\Woocommerce\Blocks\PseBlock;
use Epayco\Woocommerce\Blocks\SubscriptionBlock;
use Epayco\Woocommerce\Configs\Metadata;
use Epayco\Woocommerce\Funnel\Funnel;
use Epayco\Woocommerce\Order\OrderMetadata;
use Epayco\Woocommerce\Configs\Seller;
use Epayco\Woocommerce\Configs\Store;
use Epayco\Woocommerce\Order\OrderStatus;
use Epayco\Woocommerce\Translations\AdminTranslations;
use Epayco\Woocommerce\Translations\StoreTranslations;
use Epayco\Woocommerce\Helpers\Country;
use Epayco\Woocommerce\Helpers\Strings;

if (!defined('ABSPATH')) {
    exit;
}

class WoocommerceEpayco
{
    /**
     * @const
     */
    private const PLUGIN_VERSION = '7.6.4';

    /**
     * @const
     */
    private const PLUGIN_MIN_PHP = '7.4';

    /**
     * @const
     */
    private const PLATFORM_ID = 'bo2hnr2ic4p001kbgpt0';

    /**
     * @const
     */
    private const PRODUCT_ID_DESKTOP = 'BT7OF5FEOO6G01NJK3QG';

    /**
     * @const
     */
    private const PRODUCT_ID_MOBILE  = 'BT7OFH09QS3001K5A0H0';

    /**
     * @const
     */
    private const PLATFORM_NAME = 'woocommerce';

    /**
     * @const
     */
    private const TICKET_TIME_EXPIRATION = 3;

    /**
     * @const
     */
    private const PLUGIN_NAME = '-woocommerce-epayco-api-develop/woocommerce-epayco.php';

    /**
     * @var \WooCommerce
     */
    public $woocommerce;

    /**
     * @var Hooks
     */
    public $hooks;

    /**
     * @var Helpers
     */
    public $helpers;

    /**
     * @var Settings
     */
    public $settings;

    /**
     * @var Metadata
     */
    public $metadataConfig;

    /**
     * @var Seller
     */
    public $sellerConfig;

    /**
     * @var Store
     */
    public $storeConfig;

    /**
     * @var OrderMetadata
     */
    public $orderMetadata;

    /**
     * @var OrderStatus
     */
    public $orderStatus;

    /**
     * @var AdminTranslations
     */
    public $adminTranslations;

    /**
     * @var StoreTranslations
     */
    public $storeTranslations;

    /**
     * @var Funnel
     */
    public static $funnel;

    /**
     * @var Country
     */
    public $country;

    /**
     * WoocommerceEpayco constructor
     */
    public function __construct()
    {
        $this->defineConstants();
        $this->loadPluginTextDomain();
        $this->registerHooks();
    }

    /**
     * Load plugin text domain
     *
     * @return void
     */
    public function loadPluginTextDomain(): void
    {
        $textDomain           = 'woocommerce-epayco';
        $locale               = apply_filters('plugin_locale', get_locale(), $textDomain);
        $originalLanguageFile = dirname(__FILE__) . '/../i18n/languages/woocommerce-epayco-' . $locale . '.mo';

        unload_textdomain($textDomain);
        load_textdomain($textDomain, $originalLanguageFile);
    }

    /**
     * Register hooks
     *
     * @return void
     */
    public function registerHooks(): void
    {
        add_action('plugins_loaded', [$this, 'init']);
        add_filter('query_vars', function ($vars) {
            $vars[] = 'wallet_button';
            return $vars;
        });
    }



    /**
     * Register gateways
     *
     * @return void
     */
    public function registerGateways(): void
    {
        $gatewaysForCountry = $this->country->getOrderGatewayForCountry();
        foreach ($gatewaysForCountry as $gateway) {
            $this->hooks->gateway->registerGateway($gateway);
        }
    }

    /**
     * Register woocommerce blocks support
     *
     * @return void
     */
    public function registerBlocks(): void
    {
        if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            add_action(
                'woocommerce_blocks_payment_method_type_registration',
                function (PaymentMethodRegistry $payment_method_registry) {
                    $payment_method_registry->register(new CheckoutBlock());
                    $payment_method_registry->register(new CreditCardBlock());
                    $payment_method_registry->register(new DaviplataBlock());
                    $payment_method_registry->register(new PseBlock());
                    $payment_method_registry->register(new SubscriptionBlock());
                    $payment_method_registry->register(new TicketBlock());
                }
            );
        }
    }


    /**
     * Init plugin
     *
     * @return void
     */
    public function init(): void
    {
        if (!class_exists('WC_Payment_Gateway')) {
            $this->adminNoticeMissWoocoommerce();
            return;
        }

        /*if (!class_exists('WC_Subscriptions_Cart')) {
            $this->adminNoticeMissWoocoommerceSubscription();
            return;
        }*/

        $this->setProperties();
        $this->setPluginSettingsLink();

        if (version_compare(PHP_VERSION, self::PLUGIN_MIN_PHP, '<')) {
            $this->verifyPhpVersionNotice();
            return;
        }

        if (!$this->country->isLanguageSupportedByPlugin() && $this->helpers->notices->shouldShowNotices()) {
            $this->verifyCountryForTranslationsNotice();
        }

        $this->registerBlocks();
        $this->registerGateways();

        $this->hooks->plugin->executePluginLoadedAction();
        $this->hooks->plugin->registerActivatePlugin([$this, 'activatePlugin']);
        $this->hooks->gateway->registerSaveCheckoutSettings();
        if ($this->storeConfig->getExecuteActivate()) {
            $this->hooks->plugin->executeActivatePluginAction();
        }
    }

    /**
     * Function hook disabled plugin
     *
     * @return void
     */
    public function disablePlugin()
    {
        //self::$funnel->updateStepDisable();
    }

    /**
     * Function hook active plugin
     *
     * @return void
     */
    public function activatePlugin()
    {
        self::$funnel->isInstallationId() ? self::$funnel->updateStepActivate() : self::$funnel->getInstallationId();
    }

    /**
     * Set plugin properties
     *
     * @return void
     */
    public function setProperties(): void
    {
        $dependencies = new Dependencies();

        // Globals
        $this->woocommerce = $dependencies->woocommerce;

        // Configs
        $this->storeConfig    = $dependencies->storeConfig;
        $this->sellerConfig   = $dependencies->sellerConfig;
        $this->metadataConfig = $dependencies->metadataConfig;

        // Order
        $this->orderMetadata = $dependencies->orderMetadata;
        $this->orderStatus   = $dependencies->orderStatus;

        // Helpers
        $this->helpers = $dependencies->helpers;

        // Hooks
        $this->hooks = $dependencies->hooks;

        // Exclusive
        $this->settings = $dependencies->settings;

        // Translations
        $this->adminTranslations = $dependencies->adminTranslations;
        $this->storeTranslations = $dependencies->storeTranslations;

        // Country
        $this->country = $dependencies->countryHelper;

        self::$funnel = $dependencies->funnel;
    }

    /**
     * Set plugin configuration links
     *
     * @return void
     */
    public function setPluginSettingsLink()
    {
        $links = $this->helpers->links->getLinks();

        $pluginLinks = [
            [
                'text'   => $this->adminTranslations->plugin['set_plugin'],
                'href'   => $links['admin_settings_page'],
                'target' => $this->hooks->admin::HREF_TARGET_DEFAULT,
            ],
            [
                'text'   => $this->adminTranslations->plugin['payment_method'],
                'href'   => $links['admin_gateways_list'],
                'target' => $this->hooks->admin::HREF_TARGET_DEFAULT,
            ],
        ];

        $this->hooks->admin->registerPluginActionLinks(self::PLUGIN_NAME, $pluginLinks);
    }

    /**
     * Show php version unsupported notice
     *
     * @return void
     */
    public function verifyPhpVersionNotice(): void
    {
        $this->helpers->notices->adminNoticeError($this->adminTranslations->notices['php_wrong_version'], false);
    }


    /**
     * Show unsupported country for translations
     *
     * @return void
     */
    public function verifyCountryForTranslationsNotice(): void
    {
        $this->helpers->notices->adminNoticeError($this->adminTranslations->notices['missing_translation'], true);
    }


    /**
     * Define plugin constants
     *
     * @return void
     */
    private function defineConstants(): void
    {
        $this->define('EP_MIN_PHP', self::PLUGIN_MIN_PHP);
        $this->define('EP_VERSION', self::PLUGIN_VERSION);
        $this->define('EP_PLATFORM_ID', self::PLATFORM_ID);
        $this->define('EP_PLATFORM_NAME', self::PLATFORM_NAME);
        $this->define('EP_PRODUCT_ID_DESKTOP', self::PRODUCT_ID_DESKTOP);
        $this->define('EP_PRODUCT_ID_MOBILE', self::PRODUCT_ID_MOBILE);
        $this->define('EP_TICKET_DATE_EXPIRATION', self::TICKET_TIME_EXPIRATION);
        $this->define( 'EP_WOOCOMMERCE_VERSION', '5.3.0' );
        $this->define( 'EP_PLUGIN_URL',sprintf('%s%s', plugin_dir_url(__FILE__), '../assets/json/'));
    }

    /**
     * Define constants
     *
     * @param $name
     * @param $value
     *
     * @return void
     */
    private function define($name, $value): void
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Show woocommerce missing notice
     * This function should use WordPress features only
     *
     * @return void
     */
    public function adminNoticeMissWoocoommerce(): void
    {
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style('woocommerce-epayco-admin-notice-css');
            wp_register_style(
                'woocommerce-epayco-admin-notice-css',
                sprintf('%s%s', plugin_dir_url(__FILE__), '../assets/css/admin/ep-admin-notices.css'),
                false,
                EP_VERSION
            );
        });

        add_action(
            'admin_notices',
            function () {
                $strings = new Strings();
                $allowedHtmlTags = $strings->getAllowedHtmlTags();
                $isInstalled = false;
                $currentUserCanInstallPlugins = current_user_can('install_plugins');

                $minilogo     = sprintf('%s%s', plugin_dir_url(__FILE__), '../assets/images/logo.png');
                $translations = [
                    'activate_woocommerce' => __('Activate WooCommerce', 'woocommerce-epayco'),
                    'install_woocommerce'  => __('Install WooCommerce', 'woocommerce-epayco'),
                    'see_woocommerce'      => __('See WooCommerce', 'woocommerce-epayco'),
                    'miss_woocommerce'     => sprintf(
                        __('The ePayco module needs an active version of %s in order to work!', 'woocommerce-epayco'),
                        '<a target="_blank" href="https://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>'
                    ),
                ];

                $activateLink = wp_nonce_url(
                    self_admin_url('plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all'),
                    'activate-plugin_woocommerce/woocommerce.php'
                );

                $installLink = wp_nonce_url(
                    self_admin_url('update.php?action=install-plugin&plugin=woocommerce'),
                    'install-plugin_woocommerce'
                );

                if (function_exists('get_plugins')) {
                    $allPlugins  = get_plugins();
                    $isInstalled = !empty($allPlugins['woocommerce/woocommerce.php']);
                }

                if ($isInstalled && $currentUserCanInstallPlugins) {
                    $missWoocommerceAction = 'active';
                } else {
                    if ($currentUserCanInstallPlugins) {
                        $missWoocommerceAction = 'install';
                    } else {
                        $missWoocommerceAction = 'see';
                    }
                }

                include dirname(__FILE__) . '/../templates/admin/notices/miss-woocommerce-notice.php';
            }
        );
    }

    /**
     * Show woocommerce missing notice
     * This function should use WordPress features only
     *
     * @return void
     */
    public function adminNoticeMissWoocoommerceSubscription(): void
    {
        $url_docs = 'https://github.com/wp-premium/woocommerce-subscriptions';
        $subs = __( 'Subscription ePayco: Woocommerce subscriptions must be installed and active, ') . sprintf(__('<a target="_blank" href="%s">'. __('check documentation for help') .'</a>'), $url_docs);
        add_action(
            'admin_notices',
            function() use($subs) {
                $this->subscription_epayco_se_notices($subs);
            }
        );
    }

    public function subscription_epayco_se_notices( $notice ): void
    {
        ?>
        <div class="error notice">
            <p><?php echo $notice; ?></p>
        </div>
        <?php
    }

}
