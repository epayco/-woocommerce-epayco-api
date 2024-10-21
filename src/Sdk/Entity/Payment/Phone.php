<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;

/**
 * Class Phone
 *
 * @property string $number
 * @property string $area_code
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class Phone extends AbstractEntity
{
    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $area_code;
}
