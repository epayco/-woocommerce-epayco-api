<?php

/**
 * @var string $minilogo
 * @var string $activateLink
 * @var string $installLink
 * @var string $missWoocommerceAction
 * @var array $translations
 * @var array $allowedHtmlTags
 *
 * @see \EpaycoSubscription\Woocommerce\WoocommerceEpayco
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="message" class="notice notice-error">
    <div class="ep-alert-frame">
        <div class="ep-left-alert">
            <img src="<?php echo  esc_url($minilogo) ?>" alt="ePayco mini logo" />
        </div>

        <div class="ep-right-alert">
            <p><?php echo wp_kses($translations['miss_woocommerce'], $allowedHtmlTags) ?></p>

            <p>
                <?php if ($missWoocommerceAction === 'active') : ?>
                    <a class="button button-primary" href="<?php echo esc_html($activateLink) ?>">
                        <?php wp_kses($translations['activate_woocommerce'], $allowedHtmlTags) ?>
                    </a>
                <?php elseif ($missWoocommerceAction === 'install') : ?>
                    <a class="button button-primary" href="<?php echo esc_html($installLink) ?>">
                        <?php echo  wp_kses($translations['install_woocommerce'], $allowedHtmlTags) ?>
                    </a>
                <?php else : ?>
                    <a class="button button-primary" href="https://wordpress.org/plugins/woocommerce/">
                        <?php echo  wp_kses($translations['see_woocommerce'], $allowedHtmlTags) ?>
                    </a>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
