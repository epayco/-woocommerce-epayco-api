<?php

namespace Epayco\Woocommerce\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class CreditsBlock extends AbstractBlock
{
    /**
     * @var string
     */
    protected $scriptName = 'credits';

    /**
     * @var string
     */
    protected $name = 'woo-epayco-credits';

    /**
     * CustomBlock constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeTranslations = $this->epayco->storeTranslations->creditsCheckout;
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
