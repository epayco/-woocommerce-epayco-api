<?php

namespace Epayco\Woocommerce;

use MercadoPago\PP\Sdk\HttpClient\HttpClient;
use MercadoPago\PP\Sdk\HttpClient\Requester\CurlRequester;
use Epayco\Woocommerce\Admin\Settings;
use Epayco\Woocommerce\Configs\Metadata;
use Epayco\Woocommerce\Funnel\Funnel;
use Epayco\Woocommerce\Helpers\Actions;
use Epayco\Woocommerce\Helpers\Cart;
use Epayco\Woocommerce\Helpers\Images;
use Epayco\Woocommerce\Helpers\Session;
use Epayco\Woocommerce\Hooks\Blocks;
use Epayco\Woocommerce\Order\OrderBilling;
use Epayco\Woocommerce\Order\OrderMetadata;
use Epayco\Woocommerce\Configs\Seller;
use Epayco\Woocommerce\Configs\Store;
use Epayco\Woocommerce\Endpoints\CheckoutCustom;
use Epayco\Woocommerce\Helpers\Cache;
use Epayco\Woocommerce\Helpers\Country;
use Epayco\Woocommerce\Helpers\Cron;
use Epayco\Woocommerce\Helpers\Currency;
use Epayco\Woocommerce\Helpers\CurrentUser;
use Epayco\Woocommerce\Helpers\Gateways;
use Epayco\Woocommerce\Helpers\Links;
use Epayco\Woocommerce\Helpers\Nonce;
use Epayco\Woocommerce\Helpers\Notices;
use Epayco\Woocommerce\Helpers\Requester;
use Epayco\Woocommerce\Helpers\Strings;
use Epayco\Woocommerce\Helpers\Url;
use Epayco\Woocommerce\Helpers\PaymentMethods;
use Epayco\Woocommerce\Helpers\CreditsEnabled;
use Epayco\Woocommerce\Hooks\Admin;
use Epayco\Woocommerce\Hooks\Checkout;
use Epayco\Woocommerce\Hooks\Endpoints;
use Epayco\Woocommerce\Hooks\Gateway;
use Epayco\Woocommerce\Hooks\Options;
use Epayco\Woocommerce\Hooks\Order;
use Epayco\Woocommerce\Hooks\OrderMeta;
use Epayco\Woocommerce\Hooks\Plugin;
use Epayco\Woocommerce\Hooks\Product;
use Epayco\Woocommerce\Hooks\Scripts;
use Epayco\Woocommerce\Hooks\Template;
use Epayco\Woocommerce\Libraries\Logs\Logs;
use Epayco\Woocommerce\Libraries\Logs\Transports\File;
use Epayco\Woocommerce\Libraries\Logs\Transports\Remote;
use Epayco\Woocommerce\Order\OrderShipping;
use Epayco\Woocommerce\Order\OrderStatus;
use Epayco\Woocommerce\Translations\AdminTranslations;
use Epayco\Woocommerce\Translations\StoreTranslations;

if (!defined('ABSPATH')) {
    exit;
}

class Dependencies
{
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
     * @var CheckoutCustom
     */
    public $checkoutCustomEndpoints;

    /**
     * @var Admin
     */
    public $adminHook;

    /**
     * @var Blocks
     */
    public $blocksHook;

    /**
     * @var Hooks\Cart
     */
    public $cartHook;

    /**
     * @var Checkout
     */
    public $checkoutHook;

    /**
     * @var Endpoints
     */
    public $endpointsHook;

    /**
     * @var Gateway
     */
    public $gatewayHook;

    /**
     * @var Options
     */
    public $optionsHook;

    /**
     * @var Order
     */
    public $orderHook;

    /**
     * @var OrderMeta
     */
    public $orderMetaHook;

    /**
     * @var Plugin
     */
    public $pluginHook;

    /**
     * @var Product
     */
    public $productHook;

    /**
     * @var Scripts
     */
    public $scriptsHook;

    /**
     * @var Template
     */
    public $templateHook;

    /**
     * @var Actions
     */
    public $actionsHelper;

    /**
     * @var Cache
     */
    public $cacheHelper;

    /**
     * @var Cart
     */
    public $cartHelper;

    /**
     * @var Country
     */
    public $countryHelper;

    /**
     * @var CreditsEnabled
     */
    public $creditsEnabledHelper;

    /**
     * @var Cron
     */
    public $cronHelper;

    /**
     * @var Currency
     */
    public $currencyHelper;

    /**
     * @var CurrentUser
     */
    public $currentUserHelper;

    /**
     * @var Gateways
     */
    public $gatewaysHelper;

    /**
     * @var Images
     */
    public $imagesHelper;

    /**
     * @var Links
     */
    public $linksHelper;

    /**
     * @var Nonce
     */
    public $nonceHelper;

    /**
     * @var Notices
     */
    public $noticesHelper;

    /**
     * @var PaymentMethods
     */
    public $paymentMethodsHelper;

    /**
     * @var Requester
     */
    public $requesterHelper;

    /**
     * @var Session
     */
    public $sessionHelper;

    /**
     * @var Strings
     */
    public $stringsHelper;

    /**
     * @var Url
     */
    public $urlHelper;

    /**
     * @var Logs
     */
    public $logs;

    /**
     * @var OrderBilling
     */
    public $orderBilling;

    /**
     * @var OrderMetadata
     */
    public $orderMetadata;

    /**
     * @var OrderShipping
     */
    public $orderShipping;

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
    public $funnel;

    /**
     * Dependencies constructor
     */
    public function __construct()
    {
        global $woocommerce;

        $this->woocommerce             = $woocommerce;
        $this->adminHook               = new Admin();
        $this->cartHook                = new Hooks\Cart();
        $this->blocksHook              = new Blocks();
        $this->endpointsHook           = new Endpoints();
        $this->optionsHook             = new Options();
        $this->orderMetaHook           = new OrderMeta();
        $this->productHook             = new Product();
        $this->templateHook            = new Template();
        $this->pluginHook              = new Plugin();
        $this->checkoutHook            = new Checkout();
        $this->actionsHelper           = new Actions();
        $this->cacheHelper             = new Cache();
        $this->imagesHelper            = new Images();
        $this->sessionHelper           = new Session();
        $this->stringsHelper           = new Strings();
        $this->orderBilling            = new OrderBilling();
        $this->orderShipping           = new OrderShipping();
        $this->orderMetadata           = $this->setOrderMetadata();
        $this->requesterHelper         = $this->setRequester();
        $this->storeConfig             = $this->setStore();
        $this->logs                    = $this->setLogs();
        $this->sellerConfig            = $this->setSeller();
        $this->countryHelper           = $this->setCountry();
        $this->urlHelper               = $this->setUrl();
        $this->linksHelper             = $this->setLinks();
        $this->paymentMethodsHelper    = $this->setPaymentMethods();
        $this->scriptsHook             = $this->setScripts();
        $this->adminTranslations       = $this->setAdminTranslations();
        $this->storeTranslations       = $this->setStoreTranslations();
        $this->gatewaysHelper          = $this->setGatewaysHelper();
        $this->funnel                  = $this->setFunnel();
        $this->gatewayHook             = $this->setGateway();
        $this->nonceHelper             = $this->setNonce();
        $this->orderStatus             = $this->setOrderStatus();
        $this->cronHelper              = $this->setCronHelper();
        $this->currentUserHelper       = $this->setCurrentUser();
        $this->orderHook               = $this->setOrder();
        $this->noticesHelper           = $this->setNotices();
        $this->metadataConfig          = $this->setMetadataConfig();
        $this->currencyHelper          = $this->setCurrency();
        $this->settings                = $this->setSettings();
        $this->creditsEnabledHelper    = $this->setCreditsEnabled();
        $this->checkoutCustomEndpoints = $this->setCustomCheckoutEndpoints();
        $this->cartHelper              = $this->setCart();
        $this->funnel                  = $this->setFunnel();

        $this->hooks   = $this->setHooks();
        $this->helpers = $this->setHelpers();
    }

    /**
     * @return OrderMetadata
     */
    private function setOrderMetadata(): OrderMetadata
    {
        return new OrderMetadata($this->orderMetaHook);
    }

    /**
     * @return Requester
     */
    private function setRequester(): Requester
    {
        $curlRequester = new CurlRequester();
        $httpClient    = new HttpClient(Requester::BASEURL_MP, $curlRequester);

        return new Requester($httpClient);
    }

    /**
     * @return Seller
     */
    private function setSeller(): Seller
    {
        return new Seller($this->cacheHelper, $this->optionsHook, $this->requesterHelper, $this->storeConfig, $this->logs);
    }

    /**
     * @return Country
     */
    private function setCountry(): Country
    {
        return new Country($this->sellerConfig);
    }

    /**
     * @return Url
     */
    private function setUrl(): Url
    {
        return new Url($this->stringsHelper);
    }

    /**
     * @return Links
     */
    private function setLinks(): Links
    {
        return new Links($this->countryHelper, $this->urlHelper);
    }

    /**
     * @return PaymentMethods
     */
    private function setPaymentMethods(): PaymentMethods
    {
        return new PaymentMethods($this->urlHelper);
    }

    /**
     * @return Store
     */
    private function setStore(): Store
    {
        return new Store($this->optionsHook);
    }

    /**
     * @return Scripts
     */
    private function setScripts(): Scripts
    {
        return new Scripts($this->urlHelper, $this->sellerConfig);
    }

    /**
     * @return Gateway
     */
    private function setGateway(): Gateway
    {
        return new Gateway(
            $this->optionsHook,
            $this->templateHook,
            $this->storeConfig,
            $this->checkoutHook,
            $this->storeTranslations,
            $this->urlHelper,
            $this->funnel
        );
    }

    /**
     * @return Logs
     */
    private function setLogs(): Logs
    {
        $file   = new File($this->storeConfig);
        $remote = new Remote($this->storeConfig, $this->requesterHelper);

        return new Logs($file, $remote);
    }

    /**
     * @return Nonce
     */
    private function setNonce(): Nonce
    {
        return new Nonce($this->logs, $this->storeConfig);
    }

    /**
     * @return OrderStatus
     */
    private function setOrderStatus(): OrderStatus
    {
        return new OrderStatus($this->storeTranslations);
    }

    /**
     * @return Cron
     */
    private function setCronHelper(): Cron
    {
        return new Cron($this->logs);
    }

    /**
     * @return CurrentUser
     */
    private function setCurrentUser(): CurrentUser
    {
        return new CurrentUser($this->logs, $this->storeConfig);
    }

    /**
     * @return Gateways
     */
    private function setGatewaysHelper(): Gateways
    {
        return new Gateways($this->storeConfig);
    }

    /**
     * @return AdminTranslations
     */
    private function setAdminTranslations(): AdminTranslations
    {
        return new AdminTranslations($this->linksHelper);
    }

    /**
     * @return StoreTranslations
     */
    private function setStoreTranslations(): StoreTranslations
    {
        return new StoreTranslations($this->linksHelper);
    }

    /**
     * @return Order
     */
    private function setOrder(): Order
    {
        return new Order(
            $this->templateHook,
            $this->orderMetadata,
            $this->orderStatus,
            $this->adminTranslations,
            $this->storeTranslations,
            $this->storeConfig,
            $this->sellerConfig,
            $this->scriptsHook,
            $this->urlHelper,
            $this->nonceHelper,
            $this->endpointsHook,
            $this->cronHelper,
            $this->currentUserHelper,
            $this->requesterHelper,
            $this->logs
        );
    }

    /**
     * @return Notices
     */
    private function setNotices(): Notices
    {
        return new Notices(
            $this->scriptsHook,
            $this->adminTranslations,
            $this->urlHelper,
            $this->linksHelper,
            $this->currentUserHelper,
            $this->storeConfig,
            $this->nonceHelper,
            $this->endpointsHook,
            $this->sellerConfig
        );
    }

    /**
     * @return Metadata
     */
    private function setMetadataConfig(): Metadata
    {
        return new Metadata($this->optionsHook);
    }

    /**
     * @return Currency
     */
    private function setCurrency(): Currency
    {
        return new Currency(
            $this->adminTranslations,
            $this->cacheHelper,
            $this->countryHelper,
            $this->logs,
            $this->noticesHelper,
            $this->requesterHelper,
            $this->sellerConfig,
            $this->optionsHook,
            $this->urlHelper
        );
    }

    /**
     * @return Settings
     */
    private function setSettings(): Settings
    {
        return new Settings(
            $this->adminHook,
            $this->endpointsHook,
            $this->linksHelper,
            $this->orderHook,
            $this->pluginHook,
            $this->scriptsHook,
            $this->sellerConfig,
            $this->storeConfig,
            $this->adminTranslations,
            $this->urlHelper,
            $this->nonceHelper,
            $this->currentUserHelper,
            $this->sessionHelper,
            $this->logs,
            $this->funnel,
            $this->stringsHelper
        );
    }

    /**
     * @return CreditsEnabled
     */
    private function setCreditsEnabled(): CreditsEnabled
    {
        return new CreditsEnabled(
            $this->adminHook,
            $this->logs,
            $this->optionsHook
        );
    }

    /**
     * @return Funnel
     */
    private function setFunnel(): Funnel
    {
        return new Funnel(
            $this->storeConfig,
            $this->sellerConfig,
            $this->countryHelper,
            $this->gatewaysHelper
        );
    }

    /**
     * @return CheckoutCustom
     */
    private function setCustomCheckoutEndpoints(): CheckoutCustom
    {
        return new CheckoutCustom(
            $this->endpointsHook,
            $this->logs,
            $this->requesterHelper,
            $this->sessionHelper,
            $this->sellerConfig,
            $this->storeTranslations
        );
    }

    /**
     * @return Cart
     */
    private function setCart(): Cart
    {
        return new Cart($this->countryHelper, $this->currencyHelper, $this->sessionHelper, $this->storeTranslations);
    }

    /**
     * @return Hooks
     */
    private function setHooks(): Hooks
    {
        return new Hooks(
            $this->adminHook,
            $this->blocksHook,
            $this->cartHook,
            $this->checkoutHook,
            $this->endpointsHook,
            $this->gatewayHook,
            $this->optionsHook,
            $this->orderHook,
            $this->orderMetaHook,
            $this->pluginHook,
            $this->productHook,
            $this->scriptsHook,
            $this->templateHook
        );
    }

    private function setHelpers(): Helpers
    {
        return new Helpers(
            $this->actionsHelper,
            $this->cacheHelper,
            $this->cartHelper,
            $this->countryHelper,
            $this->creditsEnabledHelper,
            $this->currencyHelper,
            $this->currentUserHelper,
            $this->gatewaysHelper,
            $this->imagesHelper,
            $this->linksHelper,
            $this->nonceHelper,
            $this->noticesHelper,
            $this->paymentMethodsHelper,
            $this->requesterHelper,
            $this->sessionHelper,
            $this->stringsHelper,
            $this->urlHelper
        );
    }

}
