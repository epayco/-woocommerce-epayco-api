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
 * @see \Epayco\Woocommerce\Gateways\CheckoutGateway
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class='mp-checkout-container'>
    <?php if ($amount === null) : ?>
        <p style="color: red; font-weight: bold;">
            <?= esc_html($message_error_amount) ?>
        </p>
    <?php else : ?>
        <div class="mp-checkout-daviplata-container">
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
            <!--<div class="mp-checkout-ticket-terms-and-conditions">
                <terms-and-conditions
                        label="<?= esc_html($terms_and_conditions_label); ?>"
                        description="<?= esc_html($terms_and_conditions_description); ?>"
                        link-text="<?= esc_html($terms_and_conditions_link_text); ?>"
                        link-src="<?= esc_html($terms_and_conditions_link_src); ?>">
                </terms-and-conditions>
            </div>-->
        </div>
    <?php endif; ?>
</div>

