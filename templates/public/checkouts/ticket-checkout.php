<?php

/**
 * @var bool $test_mode
 * @var string $test_mode_title
 * @var string $test_mode_description
 * @var string $test_mode_link_text
 * @var string $test_mode_link_src
 * @var string $site_id
 * @var string $input_name_label
 * @var string $input_name_helper
 * @var string $input_email_label
 * @var string $input_email_helper
 * @var string $input_address_label
 * @var string $input_address_helper
 * @var string $input_ind_phone_label
 * @var string $input_ind_phone_helper
 * @var string $person_type_label
 * @var string $input_document_label
 * @var string $input_document_helper
 * @var string $ticket_text_label
 * @var string $input_table_button
 * @var string $payment_methods
 * @var string $input_helper_label
 * @var string $amount
 * @var string $currency_ratio
 * @var string $terms_and_conditions_description
 * @var string $terms_and_conditions_link_text
 * @var string $terms_and_conditions_link_src
 * @var string $amount
 * @var string $message_error_amount
 * @see \Epayco\Woocommerce\Gateways\TicketGateway
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
        <div class="mp-checkout-ticket-container">
            <div class="mp-checkout-ticket-content">
                <?php if ($test_mode) : ?>
                    <div class="mp-checkout-ticket-test-mode">
                        <test-mode
                            title="<?= esc_html($test_mode_title); ?>"
                            description="<?= esc_html($test_mode_description); ?>"
                            link-text="<?= esc_html($test_mode_link_text); ?>"
                            link-src="<?= esc_html($test_mode_link_src); ?>">
                        </test-mode>
                    </div>
                <?php endif; ?>
                <div class="mp-checkout-ticket-input-document">
                    <input-name
                            label-message="<?= esc_html($input_name_label); ?>"
                            helper-message="<?= esc_html($input_name_helper); ?>"
                            placeholder="Ex: John Doe"
                            input-name='epayco_ticket[name]'
                            flag-error='epayco_ticket[nameError]'
                            validate=true
                            hidden-id= "hidden-name-pse"
                    >
                    </input-name>
                </div>

                <div class="mp-checkout-ticket-input-document">
                    <input-email
                            label-message="<?= esc_html($input_email_label); ?>"
                            helper-message="<?= esc_html($input_email_helper); ?>"
                            placeholder="jonhdoe@example.com"
                            input-name='epayco_ticket[email]'
                            flag-error='epayco_ticket[emailError]'
                            validate=true
                            hidden-id= "hidden-email-pse"
                    >
                    </input-email>
                </div>

                <div class="mp-checkout-ticket-input-document">
                    <input-address
                            label-message="<?= esc_html($input_address_label); ?>"
                            helper-message="<?= esc_html($input_address_helper); ?>"
                            placeholder="Street 123"
                            input-name='epayco_ticket[address]'
                            flag-error='epayco_ticket[addressError]'
                            validate=true
                            hidden-id= "hidden-adress-pse"
                    >
                    </input-address>
                </div>

                <div class="mp-checkout-ticket-input-document">
                    <input-cellphone
                            label-message="<?= esc_html($input_ind_phone_label); ?>"
                            helper-message="<?= esc_html($input_ind_phone_helper); ?>"
                            input-name='epayco_ticket[cellphone]'
                            select-name='epayco_ticket[cellphoneType]'
                            select-id='cellphoneType'
                            flag-error='epayco_ticket[numberCellphoneError]'
                            documents='["+57","+1"]'
                            validate=true
                    >
                    </input-cellphone>
                </div>

                <div class="mp-checkout-ticket-input-document">
                    <input-select
                            name="epayco_ticket[person_type]"
                            label=<?= esc_html($person_type_label); ?>
                            optional="false"
                            options='[{"id":"PN", "description": "Persona natural"},{"id":"PJ", "description": "Persona jurídica"}]'
                    >
                    </input-select>
                </div>

                <div class="mp-checkout-ticket-input-document">
                    <input-document
                            label-message="<?= esc_html($input_document_label); ?>"
                            helper-message="<?= esc_html($input_document_helper); ?>"
                            input-name='epayco_ticket[doc_number]'
                            select-name='epayco_ticket[doc_type]'
                            select-id='doc_type'
                            flag-error='epayco_ticket[docNumberError]'
                            documents='["CC","CE","NIT","TI","PPN","SSN","LIC","DNI"]'
                            validate=true>
                    </input-document>
                </div>


                <p class="mp-checkout-ticket-text" data-cy="checkout-ticket-text">
                    <?= esc_html($ticket_text_label); ?>
                </p>

                <input-table
                    name="epayco_ticket[payment_method_id]"
                    button-name=<?= esc_html($input_table_button); ?>
                    columns='<?= esc_attr(wp_json_encode($payment_methods)); ?>'>
                </input-table>

                <input-helper
                    isVisible=false
                    message="<?= esc_html($input_helper_label); ?>"
                    input-id="mp-payment-method-helper"
                    id="payment-method-helper">
                </input-helper>

                <!-- NOT DELETE LOADING-->
                <div id="mp-box-loading"></div>

                <!-- utilities -->
                <div id="epayco-utilities" style="display:none;">
                    <input type="hidden" id="site_id" value="<?= esc_textarea($site_id); ?>" name="epayco_ticket[site_id]" />
                    <input type="hidden" id="ticket_amount" value="<?= esc_textarea($amount); ?>" name="epayco_ticket[amount]" />
                    <input type="hidden" id="ticket_campaign_id" name="epayco_ticket[campaign_id]" />
                    <input type="hidden" id="ticket_campaign" name="epayco_ticket[campaign]" />
                    <input type="hidden" id="ticket_discount" name="epayco_ticket[discount]" />
                </div>
            </div>

            <div class="mp-checkout-ticket-terms-and-conditions">
                <terms-and-conditions
                    description="<?= esc_html($terms_and_conditions_description); ?>"
                    link-text="<?= esc_html($terms_and_conditions_link_text); ?>"
                    link-src="<?= esc_html($terms_and_conditions_link_src); ?>">
                </terms-and-conditions>
            </div>
        </div>
    <?php endif; ?> 
</div>

<script type="text/javascript">
    if (document.getElementById("payment_method_woo-mercado-pago-custom")) {
        jQuery("form.checkout").on("checkout_place_order_woo-epayco-ticket", function() {
            cardFormLoad();
        });
    }
</script>
