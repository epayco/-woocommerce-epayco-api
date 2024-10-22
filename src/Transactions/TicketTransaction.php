<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Gateways\AbstractGateway;
use Epayco\Woocommerce\Helpers\Date;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadata;

class TicketTransaction extends AbstractPaymentTransaction
{
    /**
     * @const
     */
    public const ID = 'ticket';

    /**
     * Payment method id
     *
     * @var string
     */
    private $paymentMethodId;

    /**
     * Payment place id
     *
     * @var string
     */
    private $paymentPlaceId;

    /**
     * Ticket Transaction constructor
     *
     * @param AbstractGateway $gateway
     * @param \WC_Order $order
     * @param array $checkout
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order, array $checkout)
    {
        parent::__construct($gateway, $order, $checkout);

        $this->paymentMethodId = $this->checkout['payment_method_id'];
        $this->paymentPlaceId  = $this->epayco->helpers->paymentMethods->getPaymentPlaceId($this->paymentMethodId);
        $this->paymentMethodId = $this->epayco->helpers->paymentMethods->getPaymentMethodId($this->paymentMethodId);

        $this->transaction->installments = 1;
        $this->transaction->payment_method_id  = $this->paymentMethodId;
        $this->transaction->external_reference = $this->getExternalReference();
        $this->transaction->date_of_expiration = $this->getExpirationDate();

    }




    /**
     * Get expiration date
     *
     * @return string
     */
    public function getExpirationDate(): string
    {
        $expirationDate = $this->epayco->hooks->options->getGatewayOption(
            $this->gateway,
            'date_expiration',
            EP_TICKET_DATE_EXPIRATION
        );

        return Date::sumToNowDate($expirationDate . ' days');
    }


}
