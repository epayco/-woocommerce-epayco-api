<?php

namespace Epayco\Woocommerce\Blocks;

if (!defined('ABSPATH')) {
    exit;
}

class DaviplataBlock extends AbstractBlock
{
    /**
     * @var string
     */
    protected $scriptName = 'daviplata';

    /**
     * @var string
     */
    protected $name = 'woo-epayco-daviplata';

    /**
     * CustomBlock constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeTranslations = $this->epayco->storeTranslations->daviplataCheckout;
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
