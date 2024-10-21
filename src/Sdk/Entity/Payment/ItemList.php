<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractCollection;

/**
 * Class ItemList
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class ItemList extends AbstractCollection
{
    /**
     * Add entity to collection
     *
     * @param array $entity
     * @param string|null $key
     */
    public function add(array $entity, string $key = null)
    {
        $item = new Item($this->manager);
        $item->setEntity($entity);
        parent::addEntity($item, $key);
    }
}
