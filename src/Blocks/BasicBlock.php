<?php

namespace Epayco\Woocommerce\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class BasicBlock extends AbstractBlock
{
    /**
     * @var string
     */
    protected $scriptName = 'basic';

    /**
     * @var string
     */
    protected $name = 'woo-epayco-basic';

    /**
     * BasicBlock constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeTranslations = $this->epayco->storeTranslations->basicCheckout;
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
