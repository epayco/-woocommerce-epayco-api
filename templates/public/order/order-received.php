<?php

/**
 * Part of Woo Sdk Module
 * Author - Sdk
 * Developer
 * Copyright - Copyright(c) Sdk [https://www.epayco.com]
 * License - https://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 *
 * @package Sdk
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="mp-payment-status-container">
    <p style="font-family: 'Lato', sans-serif; font-size: 14px;">
        <?php echo esc_html($card_title); ?>
    </p>

    <div id="mp-payment-status-content" class="mp-status-sync-metabox-content" style="border-left: 4px solid <?php echo esc_html($border_left_color); ?>; min-height: 70px;">
        <!--<div class="mp-status-sync-metabox-icon" style="width: 0 !important; padding: 0 10px;">
            <img
                alt="alert"
                src="<?php echo esc_url($img_src); ?>"
                class="mp-status-sync-metabox-circle-img"
            />
        </div>-->

        <div class="mp-status-sync-metabox-text">
            <h2 class="mp-status-sync-metabox-title" style="font-weight: 700; padding: 12px 0 0 0; font-family: 'Lato', sans-serif; font-size: 16px">
                &nbsp;Estado: <?php echo esc_html($alert_title); ?>
            </h2>

            <h2 class="mp-status-sync-metabox-title" style="font-weight: 700; padding: 12px 0 0 0; font-family: 'Lato', sans-serif; font-size: 16px">
                &nbsp;<strong>Ref_payco:</strong> <?php echo esc_html($ref_payco); ?>
            </h2>

        </div>
    </div>

</div>