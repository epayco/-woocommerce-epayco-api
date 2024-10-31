<?php

namespace Epayco\Woocommerce\Admin;

use Epayco\Woocommerce\Configs\Seller;
use Epayco\Woocommerce\Configs\Store;
use Epayco\Woocommerce\Helpers\Categories;
use Epayco\Woocommerce\Helpers\CurrentUser;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Helpers\Links;
use Epayco\Woocommerce\Helpers\Nonce;
use Epayco\Woocommerce\Helpers\Session;
use Epayco\Woocommerce\Helpers\Strings;
use Epayco\Woocommerce\Helpers\Url;
use Epayco\Woocommerce\Hooks\Admin;
use Epayco\Woocommerce\Hooks\Endpoints;
use Epayco\Woocommerce\Hooks\Order;
use Epayco\Woocommerce\Hooks\Plugin;
use Epayco\Woocommerce\Hooks\Scripts;
use Epayco\Woocommerce\Translations\AdminTranslations;
use Epayco\Woocommerce\Funnel\Funnel;

if (!defined('ABSPATH')) {
    exit;
}

class Settings
{
    /**
     * @const
     */
    private const PRIORITY_ON_MENU = 90;

    /**
     * @const
     */
    private const NONCE_ID = 'ep_settings_nonce';

    /**
     * @var Admin
     */
    private $admin;

    /**
     * @var Endpoints
     */
    private $endpoints;

    /**
     * @var Links
     */
    private $links;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @var Scripts
     */
    private $scripts;

    /**
     * @var Seller
     */
    private $seller;

    /**
     * @var Store
     */
    private $store;

    /**
     * @var AdminTranslations
     */
    private $translations;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var Nonce
     */
    private $nonce;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @var Session
     */
    private $session;


    /**
     * @var Funnel
     */
    private $funnel;

    /**
     * @var Strings
     */
    private $strings;

    /**
     * Settings constructor
     *
     * @param Admin $admin
     * @param Endpoints $endpoints
     * @param Links $links
     * @param Order $order
     * @param Plugin $plugin
     * @param Scripts $scripts
     * @param Seller $seller
     * @param Store $store
     * @param AdminTranslations $translations
     * @param Url $url
     * @param Nonce $nonce
     * @param CurrentUser $currentUser
     * @param Session $session
     * @param Downloader $downloader
     * @param Funnel $funnel
     * @param Strings $strings
     */
    public function __construct(
        Admin $admin,
        Endpoints $endpoints,
        Links $links,
        Order $order,
        Plugin $plugin,
        Scripts $scripts,
        Seller $seller,
        Store $store,
        AdminTranslations $translations,
        Url $url,
        Nonce $nonce,
        CurrentUser $currentUser,
        Session $session,
        Funnel $funnel,
        Strings $strings
    ) {
        $this->admin        = $admin;
        $this->endpoints    = $endpoints;
        $this->links        = $links;
        $this->order        = $order;
        $this->plugin       = $plugin;
        $this->scripts      = $scripts;
        $this->seller       = $seller;
        $this->store        = $store;
        $this->translations = $translations;
        $this->url          = $url;
        $this->nonce        = $nonce;
        $this->currentUser  = $currentUser;
        $this->session      = $session;
        $this->funnel       = $funnel;
        $this->strings      = $strings;

        $this->loadMenu();
        $this->loadScriptsAndStyles();
        $this->registerAjaxEndpoints();

        $this->plugin->registerOnPluginCredentialsUpdate(function () {
            $this->funnel->updateStepCredentials();
        });

        $this->plugin->registerOnPluginTestModeUpdate(function () {
            $this->funnel->updateStepPluginMode();
        });

        $this->plugin->registerOnPluginStoreInfoUpdate(function () {
            $this->order->toggleSyncPendingStatusOrdersCron($this->store->getCronSyncMode());
        });
    }

    /**
     * Load admin menu
     *
     * @return void
     */
    public function loadMenu(): void
    {
        $this->admin->registerOnMenu(self::PRIORITY_ON_MENU, [$this, 'registerEpaycoInWoocommerceMenu']);
    }

    /**
     * Load scripts and styles
     *
     * @return void
     */
    public function loadScriptsAndStyles(): void
    {
        if ($this->canLoadScriptsAndStyles()) {
            $this->scripts->registerAdminStyle(
                'epayco_settings_admin_css',
                $this->url->getPluginFileUrl('assets/css/admin/ep-admin-settings', '.css')
            );

            $this->scripts->registerAdminStyle(
                'epayco_admin_configs_css',
                $this->url->getPluginFileUrl('assets/css/admin/ep-admin-configs', '.css')
            );

            $this->scripts->registerAdminScript(
                'epayco_settings_admin_js',
                $this->url->getPluginFileUrl('assets/js/admin/ep-admin-settings', '.js'),
                [
                    'nonce'              => $this->nonce->generateNonce(self::NONCE_ID),
                    'show_advanced_text' => $this->translations->storeSettings['accordion_advanced_store_show'],
                    'hide_advanced_text' => $this->translations->storeSettings['accordion_advanced_store_hide'],
                ]
            );

        }

        if ($this->canLoadScriptsNoticesAdmin()) {
            $this->scripts->registerNoticesAdminScript();
        }
    }

    /**
     * Check if scripts and styles can be loaded
     *
     * @return bool
     */
    public function canLoadScriptsAndStyles(): bool
    {
        return $this->admin->isAdmin() && (
            $this->url->validatePage('epayco-settings') ||
            $this->url->validateSection('woo-epayco')
        );
    }

    /**
     * Check if scripts notices can be loaded
     *
     * @return bool
     */
    public function canLoadScriptsNoticesAdmin(): bool
    {
        return $this->admin->isAdmin() && (
            $this->url->validateUrl('index') ||
            $this->url->validateUrl('plugins') ||
            $this->url->validatePage('wc-admin') ||
            $this->url->validatePage('wc-settings') ||
            $this->url->validatePage('epayco-settings')
        );
    }

    /**
     * Register ajax endpoints
     *
     * @return void
     */
    public function registerAjaxEndpoints(): void
    {
        $this->endpoints->registerAjaxEndpoint('ep_update_test_mode', [$this, 'epaycoUpdateTestMode']);
        $this->endpoints->registerAjaxEndpoint('ep_update_option_credentials', [$this, 'epaycoUpdateOptionCredentials']);
        $this->endpoints->registerAjaxEndpoint('ep_get_payment_methods', [$this, 'epaycoPaymentMethods']);
        $this->endpoints->registerAjaxEndpoint('ep_validate_credentials_tips', [$this, 'epaycoValidateCredentialsTips']);
        $this->endpoints->registerAjaxEndpoint('ep_validate_payment_tips', [$this, 'epaycoValidatePaymentTips']);
    }

    /**
     * Add Sdk submenu to Woocommerce menu
     *
     * @return void
     */
    public function registerEpaycoInWoocommerceMenu(): void
    {
        $this->admin->registerSubmenuPage(
            'woocommerce',
            'ePayco Settings',
            'ePayco',
            'manage_options',
            'epayco-settings',
            [$this, 'ePaycoSubmenuPageCallback']
        );
    }

    /**
     * Show plugin configuration page
     *
     * @return void
     */
    public function ePaycoSubmenuPageCallback(): void
    {
        $headerTranslations      = $this->translations->headerSettings;
        $credentialsTranslations = $this->translations->credentialsSettings;
        $storeTranslations       = $this->translations->storeSettings;
        $gatewaysTranslations    = $this->translations->gatewaysSettings;
        $testModeTranslations    = $this->translations->testModeSettings;
        $allowedHtmlTags         = $this->strings->getAllowedHtmlTags();

        $pcustid   = $this->seller->getCredentialsPCustId();
        $pKey   = $this->seller->getCredentialsPkey();
        $publicKey   = $this->seller->getCredentialsPublicKeyPayment();
        $privateKey   = $this->seller->getCredentialsPrivateKeyPayment();

        $storeId             = $this->store->getStoreId();
        $storeName           = $this->store->getStoreName();
        $storeCategory       = $this->store->getStoreCategory('others');
        $customDomain        = $this->store->getCustomDomain();
        $customDomainOptions = $this->store->getCustomDomainOptions();
        $integratorId        = $this->store->getIntegratorId();

        $checkboxCheckoutTestMode       = $this->store->getCheckboxCheckoutTestMode();
        $checkboxCheckoutProductionMode = $this->store->getCheckboxCheckoutProductionMode();

        $links      = $this->links->getLinks();
        $testMode   = ($checkboxCheckoutTestMode === 'yes');
        $categories = Categories::getCategories();

        $phpVersion = phpversion() ? phpversion() : "";
        $wpVersion = $GLOBALS['wp_version'] ? $GLOBALS['wp_version'] : "";
        $wcVersion = $GLOBALS['woocommerce']->version ? $GLOBALS['woocommerce']->version : "";
        $pluginVersion = EP_VERSION ? EP_VERSION : "";


        include dirname(__FILE__) . '/../../templates/admin/settings/settings.php';
    }



    /**
     * Get available payment methods
     *
     * @return void
     */
    public function epaycoPaymentMethods(): void
    {
        try {
            $this->validateAjaxNonce();

            $paymentGateways            = $this->store->getAvailablePaymentGateways();
            $payment_gateway_properties = [];

            foreach ($paymentGateways as $paymentGateway) {
                $gateway = new $paymentGateway();

                $payment_gateway_properties[] = [
                    'id'               => $gateway->id,
                    'title_gateway'    => $gateway->title,
                    'description'      => $gateway->description,
                    'title'            => $gateway->title,
                    'enabled'          => !isset($gateway->settings['enabled']) ? false : $gateway->settings['enabled'],
                    'icon'             => $gateway->iconAdmin,
                    'link'             => admin_url('admin.php?page=wc-settings&tab=checkout&section=') . $gateway->id,
                    'badge_translator' => [
                        'yes' => $this->translations->gatewaysSettings['enabled'],
                        'no'  => $this->translations->gatewaysSettings['disabled'],
                    ],
                ];
            }

            wp_send_json_success($payment_gateway_properties);
        } catch (\Exception $e) {
            $response = [
                'message' => $e->getMessage()
            ];
            wp_send_json_error($response);
        }
    }

    /**
     * Validate store tips
     *
     * @return void
     */
    public function epaycoValidatePaymentTips(): void
    {
        $this->validateAjaxNonce();

        $paymentGateways = $this->store->getAvailablePaymentGateways();

        foreach ($paymentGateways as $gateway) {
            $gateway = new $gateway();

            if (isset($gateway->settings['enabled']) && 'yes' === $gateway->settings['enabled']) {
                wp_send_json_success($this->translations->configurationTips['valid_payment_tips']);
            }
        }

        wp_send_json_error($this->translations->configurationTips['invalid_payment_tips']);
    }


    /**
     * Validate credentials tips
     *
     * @return void
     */
    public function epaycoValidateCredentialsTips(): void
    {
        $this->validateAjaxNonce();

        $p_cust_id = $this->seller->getCredentialsPCustId();
        $publicKey = $this->seller->getCredentialsPublicKeyPayment();
        $privateKey = $this->seller->getCredentialsPrivateKeyPayment();
        $p_key = $this->seller->getCredentialsPkey();

        if ($p_cust_id && $publicKey && $privateKey && $p_key) {
            wp_send_json_success($this->translations->configurationTips['valid_credentials_tips']);
        }

        wp_send_json_error($this->translations->configurationTips['invalid_credentials_tips']);
    }




    /**
     * Save credentials and seller options
     *
     * @return void
     */
    public function epaycoUpdateOptionCredentials(): void
    {
        try {
            $this->validateAjaxNonce();

            $p_cust_id   = Form::sanitizedPostData('p_cust_id')??$_POST['p_cust_id'];
            $p_key   = Form::sanitizedPostData('p_key')??$_POST['p_key'];
            $publicKey   = Form::sanitizedPostData('publicKey')??$_POST['publicKey'];
            $private_key   = Form::sanitizedPostData('private_key')??$_POST['private_key'];

            $this->seller->validatePublicKeyPayment('p_cust_id', $p_cust_id);
            $this->seller->validatePublicKeyPayment('p_key', $p_key);
            $this->seller->validatePublicKeyPayment('publicKey', $publicKey);
            $this->seller->validatePublicKeyPayment('private_key', $private_key);

            $validateEpaycoCredentials =  $this->seller->validateEpaycoCredentials($publicKey, $private_key);

            if ($validateEpaycoCredentials['status']) {
                $this->seller->setCredentialsPCustId($p_cust_id);
                $this->seller->setCredentialsPkey($p_key);
                $this->seller->setCredentialsPublicKeyPayment($publicKey);
                $this->seller->setCredentialsPrivateKeyPayment($private_key);
                $this->plugin->executeUpdateCredentialAction();
                wp_send_json_success($this->translations->updateCredentials['credentials_updated']);
            }else{
                $response = [
                    'type'      => 'error',
                    'message'   => $this->translations->updateCredentials['invalid_credentials_title'],
                    'subtitle'  => $this->translations->updateCredentials['invalid_credentials_subtitle'] . ' ',
                    'linkMsg'   => $this->translations->updateCredentials['invalid_credentials_link_message'],
                    'link'      => $this->links->getLinks()['docs_integration_credentials'],
                    'test_mode' => $this->store->getCheckboxCheckoutTestMode()
                ];
                wp_send_json_error($response);
            }


        } catch (\Exception $e) {
            $response = [
                'type'      => 'error',
                'message'   => $e->getMessage(),
                'subtitle'  => $e->getMessage() . ' ',
                'linkMsg'   => '',
                'link'      => '',
                'test_mode' => $this->store->getCheckboxCheckoutTestMode()
            ];
            wp_send_json_error($response);
        }
    }


    /**
     * Save test mode options
     *
     * @return void
     */
    public function epaycoUpdateTestMode(): void
    {
        $this->validateAjaxNonce();

        $checkoutTestMode    = Form::sanitizedPostData('input_mode_value');

        $validateCheckoutTestMode = ($checkoutTestMode === 'yes');

        $withoutTestCredentials = (
            $this->seller->getCredentialsPublicKeyPayment() === '' ||
            $this->seller->getCredentialsPrivateKeyPayment() === ''
        );

        if ( $withoutTestCredentials ) {
            wp_send_json_error($this->translations->updateCredentials['invalid_credentials_title'] .
                $this->translations->updateCredentials['for_test_mode']);
        }

        $this->store->setCheckboxCheckoutTestMode($checkoutTestMode);

        $this->plugin->executeUpdateTestModeAction();

        if ($validateCheckoutTestMode) {
            wp_send_json_success($this->translations->testModeSettings['title_message_test']);
        }

        wp_send_json_success($this->translations->testModeSettings['title_message_prod']);
    }

    /**
     * Validate ajax nonce
     *
     * @return void
     */
    private function validateAjaxNonce(): void
    {
        $this->nonce->validateNonce(self::NONCE_ID, Form::sanitizedPostData('nonce'));
        $this->currentUser->validateUserNeededPermissions();
    }


}
