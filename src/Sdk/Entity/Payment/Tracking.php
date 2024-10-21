<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;

/**
 * Class Tracking
 *
 * @property string $code
 * @property string $status
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class Tracking extends AbstractEntity
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $status;
}
