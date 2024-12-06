<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Gateways\AbstractGateway;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadata;

class WalletButtonTransaction extends AbstractPreferenceTransaction
{
    /**
     * @const
     */
    public const ID = 'wallet_button';

    /**
     * Wallet Button Transaction constructor
     *
     * @param AbstractGateway $gateway
     * @param \WC_Order $order
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order)
    {
        parent::__construct($gateway, $order);

        $this->transaction->auto_return = null;
        $this->transaction->purpose = 'wallet_purchase';
    }

    /**
     * Get internal metadata
     *
     * @return PaymentMetadata
     */
    public function getInternalMetadata(): PaymentMetadata
    {
        $internalMetadata = parent::getInternalMetadata();

        $internalMetadata->checkout      = 'pro';
        $internalMetadata->checkout_type = self::ID;

        return $internalMetadata;
    }
}
