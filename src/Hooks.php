<?php

namespace Epayco\Woocommerce;

use Epayco\Woocommerce\Hooks\Admin;
use Epayco\Woocommerce\Hooks\Blocks;
use Epayco\Woocommerce\Hooks\Cart;
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

if (!defined('ABSPATH')) {
    exit;
}

class Hooks
{
    /**
     * @var Admin
     */
    public $admin;

    /**
     * @var Blocks
     */
    public $blocks;

    /**
     * @var Cart
     */
    public $cart;

    /**
     * @var Checkout
     */
    public $checkout;

    /**
     * @var Endpoints
     */
    public $endpoints;

    /**
     * @var Gateway
     */
    public $gateway;

    /**
     * @var Options
     */
    public $options;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var OrderMeta
     */
    public $orderMeta;

    /**
     * @var Plugin
     */
    public $plugin;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var Scripts
     */
    public $scripts;

    /**
     * @var Template
     */
    public $template;

    public function __construct(
        Admin $admin,
        Blocks $blocks,
        Cart $cart,
        Checkout $checkout,
        Endpoints $endpoints,
        Gateway $gateway,
        Options $options,
        Order $order,
        OrderMeta $orderMeta,
        Plugin $plugin,
        Product $product,
        Scripts $scripts,
        Template $template
    ) {
        $this->admin     = $admin;
        $this->blocks    = $blocks;
        $this->cart      = $cart;
        $this->checkout  = $checkout;
        $this->endpoints = $endpoints;
        $this->gateway   = $gateway;
        $this->options   = $options;
        $this->order     = $order;
        $this->orderMeta = $orderMeta;
        $this->plugin    = $plugin;
        $this->product   = $product;
        $this->scripts   = $scripts;
        $this->template  = $template;
    }
}
