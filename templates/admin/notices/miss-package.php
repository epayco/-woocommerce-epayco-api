<?php

/**
 * @var string $path
 * @see \Epayco\Woocommerce\Startup
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="notice notice-error">
    <p>
        <b>Missing the Epayco <code> <?= esc_html($path) ?></code> package.</b>
    </p>
    <p>Your installation of Epayco is incomplete.</p>
</div>
