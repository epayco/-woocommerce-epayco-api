<?php

namespace Epayco\Woocommerce\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class CheckoutBlock extends AbstractBlock
{
    /**
     * @var string
     */
    protected $scriptName = 'checkout';

    /**
     * @var string
     */
    protected $name = 'woo-epayco-checkout';

    /**
     * CustomBlock constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeTranslations = $this->epayco->storeTranslations->epaycoCheckout;
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
