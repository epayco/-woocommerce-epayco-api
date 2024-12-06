<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Gateways\AbstractGateway;

abstract class AbstractPreferenceTransaction extends AbstractTransaction
{
    /**
     * Preference Transaction constructor
     *
     * @param AbstractGateway $gateway
     * @param \WC_Order $order
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order)
    {
        parent::__construct($gateway, $order);

        $this->setCommonTransaction();
    }


    /**
     * Set common transaction
     *
     * @return void
     */
    public function setCommonTransaction(): void
    {
        parent::setCommonTransaction();

        $isTestMode = $this->epayco->storeConfig->isTestMode();
        $isTestUser = $this->epayco->sellerConfig->isTestUser();

        if (!$isTestMode && !$isTestUser) {
            $this->transaction->sponsor_id = $this->countryConfigs['sponsor_id'];
        }
    }

}
