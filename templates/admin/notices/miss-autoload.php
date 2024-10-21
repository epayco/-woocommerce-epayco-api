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
        <b>Unable to find composer autoloader on <code><?= esc_html($path) ?></code></b>
    </p>
    <p>Your installation of ePayco is incomplete.</p>
</div>
