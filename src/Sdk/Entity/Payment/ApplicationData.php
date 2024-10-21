<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;

/**
 * Class ApplicationData
 *
 * @property string $name
 * @property string $version
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class ApplicationData extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $version;
}
