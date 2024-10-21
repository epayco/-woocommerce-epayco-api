<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;

/**
 * Class BankInfo
 *
 * @property string $origin_bank_id
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class BankInfo extends AbstractEntity
{
    /**
     * @var string
     */
    protected $origin_bank_id;
}
