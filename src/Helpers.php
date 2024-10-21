<?php

namespace Epayco\Woocommerce;

use Epayco\Woocommerce\Helpers\Actions;
use Epayco\Woocommerce\Helpers\Cache;
use Epayco\Woocommerce\Helpers\Cart;
use Epayco\Woocommerce\Helpers\Country;
use Epayco\Woocommerce\Helpers\CreditsEnabled;
use Epayco\Woocommerce\Helpers\Currency;
use Epayco\Woocommerce\Helpers\CurrentUser;
use Epayco\Woocommerce\Helpers\Gateways;
use Epayco\Woocommerce\Helpers\Images;
use Epayco\Woocommerce\Helpers\Links;
use Epayco\Woocommerce\Helpers\Nonce;
use Epayco\Woocommerce\Helpers\Notices;
use Epayco\Woocommerce\Helpers\PaymentMethods;
use Epayco\Woocommerce\Helpers\Requester;
use Epayco\Woocommerce\Helpers\Session;
use Epayco\Woocommerce\Helpers\Strings;
use Epayco\Woocommerce\Helpers\Url;

if (!defined('ABSPATH')) {
    exit;
}

class Helpers
{
    /**
     * @var Actions
     */
    public $actions;

    /**
     * @var Cache
     */
    public $cache;

    /**
     * @var Cart
     */
    public $cart;

    /**
     * @var Country
     */
    public $country;

    /**
     * @var CreditsEnabled
     */
    public $creditsEnabled;

    /**
     * @var Currency
     */
    public $currency;

    /**
     * @var CurrentUser
     */
    public $currentUser;

    /**
     * @var Gateways
     */
    public $gateways;

    /**
     * @var Images
     */
    public $images;

    /**
     * @var Links
     */
    public $links;

    /**
     * @var Nonce
     */
    public $nonce;

    /**
     * @var Notices
     */
    public $notices;

    /**
     * @var PaymentMethods
     */
    public $paymentMethods;

    /**
     * @var Requester
     */
    public $requester;

    /**
     * @var Session
     */
    public $session;

    /**
     * @var Strings
     */
    public $strings;

    /**
     * @var Url
     */
    public $url;

    public function __construct(
        Actions $actions,
        Cache $cache,
        Cart $cart,
        Country $country,
        CreditsEnabled $creditsEnabled,
        Currency $currency,
        CurrentUser $currentUser,
        Gateways $gateways,
        Images $images,
        Links $links,
        Nonce $nonce,
        Notices $notices,
        PaymentMethods $paymentMethods,
        Requester $requester,
        Session $session,
        Strings $strings,
        Url $url
    ) {
        $this->actions        = $actions;
        $this->cache          = $cache;
        $this->cart           = $cart;
        $this->country        = $country;
        $this->creditsEnabled = $creditsEnabled;
        $this->currency       = $currency;
        $this->currentUser    = $currentUser;
        $this->gateways       = $gateways;
        $this->images         = $images;
        $this->links          = $links;
        $this->nonce          = $nonce;
        $this->notices        = $notices;
        $this->paymentMethods = $paymentMethods;
        $this->requester      = $requester;
        $this->session        = $session;
        $this->strings        = $strings;
        $this->url            = $url;
    }
}
