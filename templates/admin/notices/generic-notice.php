<?php

/**
 * @var string $type
 * @var string $minilogo
 * @var string $message
 * @var bool $isDismissible
 *
 * @see \Epayco\Woocommerce\Helpers\Notices
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="message" class="notice <?= esc_attr($type) ?> <?= esc_attr($isDismissible) ?>">
    <div class="mp-alert-frame">
        <div class="mp-left-alert">
            <img src="<?= esc_url($minilogo) ?>" alt="ePayco Pago mini logo" />
        </div>

        <div class="mp-right-alert">
            <p><?= wp_kses_post($message) ?></p>
        </div>
    </div>
</div>
