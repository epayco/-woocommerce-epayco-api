<?php

namespace Epayco\Woocommerce\Libraries\Logs;

use Epayco\Woocommerce\Libraries\Logs\Transports\File;
use Epayco\Woocommerce\Libraries\Logs\Transports\Remote;

if (!defined('ABSPATH')) {
    exit;
}

class Logs
{
    /**
     * @var File
     */
    public $file;

    /**
     * @var Remote
     */
    public $remote;

    /**
     * Logs constructor
     *
     * @param File $file
     * @param Remote $remote
     */
    public function __construct(File $file, Remote $remote)
    {
        $this->file   = $file;
        $this->remote = $remote;
    }
}
