<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;

/**
 * Class Item
 *
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $picture_url
 * @property string $category_id
 * @property int $quantity
 * @property float $unit_price
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class Item extends AbstractEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $picture_url;

    /**
     * @var string
     */
    protected $category_id;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $unit_price;
}
