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

<div class="ep-card-info">
    <div class="<?php echo  esc_html($settings['value']['color_card']); ?>"></div>

    <div class="ep-card-body-payments <?php echo  esc_html($settings['value']['size_card']); ?>">
        <!--<div class="<?php echo  esc_html($settings['value']['icon']); ?>"></div>-->
        <img src="<?php echo  esc_html($settings['value']['icon']); ?>" alt="info" style="height: 25px;margin: 15px">
        <div>
            <span class="ep-text-title"><b><?php echo  esc_html($settings['value']['title']); ?></b></span>
            <span class="ep-text-subtitle"><?php echo  wp_kses($settings['value']['subtitle'], 'b'); ?></span>
            <a class="ep-button-payments-a" target="<?php echo  esc_html($settings['value']['target']); ?>"
               href="<?php echo  esc_html($settings['value']['button_url']); ?>">
                <button type="button"
                        class="ep-button-payments"><?php echo  esc_html($settings['value']['button_text']); ?></button>
            </a>
        </div>
    </div>
</div>
