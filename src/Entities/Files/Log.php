<?php

namespace Epayco\Woocommerce\Entities\Files;

if (!defined('ABSPATH')) {
    exit;
}

class Log
{
    /**
     * @var string
     */
    public $fileName;

    /**
     * @var string
     */
    public $fileDate;

    /**
     * @var string
     */
    public $fileFullName;
}
