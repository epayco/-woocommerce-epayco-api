<?php

namespace Epayco\Woocommerce\Sdk\Entity\Payment;

use Epayco\Woocommerce\Sdk\Common\AbstractEntity;
use Epayco\Woocommerce\Sdk\Common\Manager;

/**
 * Class TransactionData
 *
 * @property BankInfo $bank_info
 *
 * @package Epayco\Woocommerce\Sdk\Entity\Payment
 */
class TransactionData extends AbstractEntity
{
    /**
     * @var BankInfo
     */
    protected $bank_info;

    /**
     * @var string
     */
    protected $qr_code_base64;

    /**
     * @var string
     */
    protected $qr_code;

    /**
     * TransactionData constructor.
     *
     * @param Manager|null $manager
     */
    public function __construct($manager)
    {
        parent::__construct($manager);
        $this->bank_info = new BankInfo($manager);
    }
}
