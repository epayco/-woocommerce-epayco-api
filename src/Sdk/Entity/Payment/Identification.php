<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;

/**
 * Class Identification
 *
 * @property string $type
 * @property string $number
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class Identification extends AbstractEntity
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $number;
}
