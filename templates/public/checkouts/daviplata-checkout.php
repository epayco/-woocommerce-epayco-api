<?php

/**
 * @var bool $test_mode
 * @var string $test_mode
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
 * @var string $terms_and_conditions_label
 * @var string $terms_and_conditions_description
 * @var string $terms_and_conditions_link_text
 * @var string $terms_and_conditions_link_src
 * @var string $amount
 * @var string $message_error_amount
 *
 * @see \Epayco\Woocommerce\Gateways\CustomGateway
 */

if (!defined('ABSPATH')) {
    exit;
}

?>
<div class="mp-checkout-custom-load">
    <div class="spinner-card-form"></div>
</div>
<div class='mp-checkout-container'>
    <?php if ($amount === null) : ?>
        <p style="color: red; font-weight: bold;">
            <?= esc_html($message_error_amount) ?>
        </p>
    <?php else : ?>
        <div class="mp-checkout-daviplata-container">
            <div class="mp-checkout-daviplata-content">
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
                            input-name='epayco_daviplata[name]'
                            flag-error='epayco_daviplata[nameError]'
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
                            input-name='epayco_daviplata[email]'
                            flag-error='epayco_daviplata[emailError]'
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
                            input-name='epayco_daviplata[address]'
                            flag-error='epayco_daviplata[addressError]'
                            validate=true
                            hidden-id= "hidden-adress-pse"
                    >
                    </input-address>
                </div>

                <div class="mp-checkout-ticket-input-document">
                    <input-cellphone
                            label-message="<?= esc_html($input_ind_phone_label); ?>"
                            helper-message="<?= esc_html($input_ind_phone_helper); ?>"
                            input-name='epayco_daviplata[cellphone]'
                            hidden-id="cellphoneType"
                            input-data-checkout="doc_number"
                            select-id="cellphoneType"
                            input-id="cellphoneTypeNumber"
                            select-name="cellphoneType"
                            select-data-checkout="doc_type"
                            flag-error="cellphoneTypeError"
                            flag-error='epayco_daviplata[cellphoneTypeError]'
                            validate=true
                            placeholder="0000000000"
                    >
                    </input-cellphone>
                </div>

                <div class="mp-checkout-ticket-input-document">
                    <input-select
                            name="epayco_daviplata[person_type]"
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
                            input-name='epayco_daviplata[doc_number]'
                            hidden-id="dentificationType"
                            input-data-checkout="doc_number"
                            select-id="dentificationType"
                            input-id="dentificationTypeNumber"
                            select-name="identificationType"
                            select-data-checkout="doc_type"
                            flag-error="docNumberError"
                            flag-error='epayco_daviplata[docNumberError]'
                            documents='[
                                    {"id":"Type"},
                                    {"id":"CC"},
                                    {"id":"CE"},
                                    {"id":"NIT"},
                                    {"id":"TI"},
                                    {"id":"PPN"},
                                    {"id":"SSN"},
                                    {"id":"LIC"},
                                    {"id":"DNI"}
                                    ]'
                            validate=true
                            placeholder="0000000000"
                    >
                    </input-document>
                </div>



                <!-- NOT DELETE LOADING-->
                <div id="mp-box-loading"></div>

                <!-- utilities -->
                <div id="epayco-utilities" style="display:none;">
                    <input type="hidden" id="site_id" value="<?= esc_textarea($site_id); ?>" name="epayco_daviplata[site_id]" />
                    <input type="hidden" id="ticket_amount" value="<?= esc_textarea($amount); ?>" name="epayco_daviplata[amount]" />
                    <input type="hidden" id="ticket_currency_ratio" value="<?= esc_textarea($currency_ratio); ?>" name="epayco_daviplata[currency_ratio]" />
                    <input type="hidden" id="ticket_campaign_id" name="epayco_daviplata[campaign_id]" />
                    <input type="hidden" id="ticket_campaign" name="epayco_daviplata[campaign]" />
                    <input type="hidden" id="ticket_discount" name="epayco_daviplata[discount]" />
                </div>
            </div>

            <div class="mp-checkout-ticket-terms-and-conditions">
                <terms-and-conditions
                        label="<?= esc_html($terms_and_conditions_label); ?>"
                        description="<?= esc_html($terms_and_conditions_description); ?>"
                        link-text="<?= esc_html($terms_and_conditions_link_text); ?>"
                        link-src="<?= esc_html($terms_and_conditions_link_src); ?>">
                </terms-and-conditions>
            </div>
        </div>
    <?php endif; ?>

</div>

<div id="epayco-utilities" style="display:none;">
    <input type="hidden" id="mp-amount" value='<?= esc_textarea($amount); ?>' name="epayco_custom[amount]"/>
    <input type="hidden" id="paymentMethodId" name="epayco_custom[payment_method_id]"/>
    <input type="hidden" id="mp_checkout_type" name="epayco_custom[checkout_type]" value="custom"/>
    <input type="hidden" id="cardholderName" data-checkout="cardholderName" value="form-checkout__cardholderName" name="epayco_custom[cardholderName]"/>
</div>



