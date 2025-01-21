<?php

/**
 * @var bool $test_mode
 * @var string $test_mode_title
 * @var string $test_mode_description
 * @var string $amount
 * @var string $message_error_amount
 * @var string $terms_and_conditions_label
 * @var string $terms_and_conditions_description
 * @var string $terms_and_conditions_link_text
 * @var string $terms_and_conditions_link_src
 * @var string $personal_data_processing_link_text
 * @var string $personal_data_processing_link_src
 * @var string $and_the
 * @see \Epayco\Woocommerce\Gateways\CheckoutGateway
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class='mp-checkout-container'>
    <div class="mp-checkout-epayco-container">
        <div class="mp-checkout-epayco-content">
            <?php if ($test_mode) : ?>
                <div class="mp-checkout-ticket-test-mode">
                    <test-mode
                        title="<?= esc_html($test_mode_title); ?>"
                        description="<?= esc_html($test_mode_description); ?>"
                    </test-mode>
                </div>
            <?php endif; ?>
            <!-- NOT DELETE LOADING-->
            <div id="mp-box-loading"></div>
        </div>
    </div>
</div>

