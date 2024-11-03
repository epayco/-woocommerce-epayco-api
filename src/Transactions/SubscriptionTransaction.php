<?php

namespace Epayco\Woocommerce\Subscription;

use Epayco\Woocommerce\Gateways\AbstractGateway;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadata;

class SubscriptionTransaction extends AbstractPaymentTransaction
{
    /**
     * @const
     */
    public const ID = 'subscription';

    /**
     * Custom Transaction constructor
     *
     * @param AbstractGateway $gateway
     * @param \WC_Order $order
     * @param array $checkout
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order, array $checkout)
    {
        parent::__construct($gateway, $order, $checkout);

    }

    /**
     * Get internal metadata
     *
     * @return PaymentMetadata
     */
    public function getInternalMetadata(): PaymentMetadata
    {
        $internalMetadata = parent::getInternalMetadata();

        $internalMetadata->checkout      = 'subscription';
        $internalMetadata->checkout_type = self::ID;

        return $internalMetadata;
    }


}
