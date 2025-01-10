<?php

namespace Epayco\Woocommerce\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class SubscriptionBlock extends AbstractBlock
{
    /**
     * @var string
     */
    protected $scriptName = 'subscription';

    /**
     * @var string
     */
    protected $name = 'woo-epayco-subscription';

    /**
     * CustomBlock constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeTranslations = $this->epayco->storeTranslations->subscriptionCheckout;
    }

    /**
     * Set payment block script params
     *
     * @return array
     */
    public function getScriptParams(): array
    {
        return $this->gateway->getPaymentFieldsParams();
    }
}