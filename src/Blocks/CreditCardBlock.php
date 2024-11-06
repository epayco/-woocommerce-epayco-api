<?php

namespace Epayco\Woocommerce\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class CreditCardBlock extends AbstractBlock
{
    /**
     * @var string
     */
    protected $scriptName = 'creditcard';

    /**
     * @var string
     */
    protected $name = 'woo-epayco-creditcard';

    /**
     * CustomBlock constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeTranslations = $this->epayco->storeTranslations->creditcardCheckout;
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
