<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Gateways\AbstractGateway;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadata;

class PseTransaction extends AbstractPaymentTransaction
{
    /**
     * @const
     */
    public const ID = 'pse';

    /**
     * PSE Transaction constructor
     *
     * @param AbstractGateway $gateway
     * @param \WC_Order $order
     * @param array $checkout
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order, array $checkout)
    {
        parent::__construct($gateway, $order, $checkout);

        $this->transaction->payment_method_id          = self::ID;
        $this->transaction->installments               = 1;
    }

    /**
     * Get internal metadata
     *
     * @return PaymentMetadata
     */
    public function getInternalMetadata(): PaymentMetadata
    {

        $internalMetadata = parent::getInternalMetadata();

        $internalMetadata->checkout      = 'credits';
        $internalMetadata->checkout_type = self::ID;

        return $internalMetadata;
    }

}
