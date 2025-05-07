<?php

/**
 * @var array $settings
 *
 * @see \Epayco\Woocommerce\Gateways\AbstractGateway
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="ep-preview">
    <p class="description">
        <?php echo esc_html($settings['description']); ?>
    </p>
    <img src="<?php echo esc_url($settings['url']); ?>" alt="Preview image">
</div>
