<?php

/**
 * @var bool $test_mode
 * @var string $test_mode_title
 * @var string $test_mode_description
 * @var string $test_mode_link_text
 * @var string $test_mode_link_src
 * @var string $input_name_label
 * @var string $input_name_helper
 * @var string $input_email_label
 * @var string $input_email_helper
 * @var string $input_address_label
 * @var string $input_address_helper
 * @var string $input_document_label
 * @var string $input_document_helper
 * @var string $input_ind_phone_label
 * @var string $input_ind_phone_helper
 * @var string $input_table_button
 * @var string $payment_methods
 * @var string $amount
 * @var string $currency_ratio
 * @var array  $financial_institutions
 * @var string $person_type_label
 * @var string $financial_institutions_label
 * @var string $financial_institutions_helper
 * @var string $financial_placeholder
 * @var string $site_id
 * @var string $terms_and_conditions_description
 * @var string $terms_and_conditions_link_text
 * @var string $terms_and_conditions_link_src
 * @var string $amount
 * @var string $message_error_amount
 * @see \Epayco\Woocommerce\Gateways\PseGateway
 */

if (! defined('ABSPATH')) {
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
            <div class="mp-checkout-pse-content">
                <?php if ($test_mode) : ?>
                    <div class="mp-checkout-pse-test-mode">
                        <test-mode
                            title="<?= esc_html($test_mode_title); ?>"
                            description="<?= esc_html($test_mode_description); ?>"
                            link-text="<?= esc_html($test_mode_link_text); ?>"
                            link-src="<?= esc_html($test_mode_link_src); ?>">
                        </test-mode>
                    </div>
                <?php endif; ?>
                <div class='mp-checkout-pse-input-cellphone'>
                    <input-name
                            label-message="<?= esc_html($input_name_label); ?>"
                            helper-message="<?= esc_html($input_name_helper); ?>"
                            placeholder="Ex: John Doe"
                            input-name='epayco_pse[name]'
                            flag-error='epayco_pse[nameError]'
                            validate=true
                            hidden-id= "hidden-name-pse"
                    >
                    </input-name>
                </div>
                <div class='mp-checkout-pse-input-cellphone'>
                    <input-email
                            label-message="<?= esc_html($input_email_label); ?>"
                            helper-message="<?= esc_html($input_email_helper); ?>"
                            placeholder="jonhdoe@example.com"
                            input-name='epayco_pse[email]'
                            flag-error='epayco_pse[emailError]'
                            validate=true
                            hidden-id= "hidden-email-pse"
                    >
                    </input-email>
                </div>
                <div class='mp-checkout-pse-input-cellphone'>
                    <input-address
                            label-message="<?= esc_html($input_address_label); ?>"
                            helper-message="<?= esc_html($input_address_helper); ?>"
                            placeholder="Street 123"
                            input-name='epayco_pse[address]'
                            flag-error='epayco_pse[addressError]'
                            validate=true
                            hidden-id= "hidden-adress-pse"
                    >
                    </input-address>
                </div>
                <div class='mp-checkout-pse-input-cellphone'>
                    <input-cellphone
                            label-message="<?= esc_html($input_ind_phone_label); ?>"
                            helper-message="<?= esc_html($input_ind_phone_helper); ?>"
                            input-name='epayco_pse[cellphone]'
                            select-name='epayco_pse[cellphoneType]'
                            select-id='cellphoneType'
                            flag-error='epayco_pse[numberCellphoneError]'
                            documents='["+57","+1"]'
                            validate=true
                    >
                    </input-cellphone>
                </div>
                <div class="mp-checkout-pse-person">
                    <input-select
                        name="epayco_pse[person_type]"
                        label=<?= esc_html($person_type_label); ?>
                        optional="false"
                        options='[{"id":"PN", "description": "Persona natural"},{"id":"PJ", "description": "Persona jurídica"}]'
                    >
                    </input-select>
                </div>
                    <div class="mp-checkout-pse-input-document">
                        <input-document
                            label-message="<?= esc_html($input_document_label); ?>"
                            helper-message="<?= esc_html($input_document_helper); ?>"
                            input-name='epayco_pse[doc_number]'
                            select-name='epayco_pse[doc_type]'
                            select-id='doc_type'
                            flag-error='epayco_pse[docNumberError]'
                            documents='["CC","CE","NIT","TI","PPN","SSN","LIC","DNI"]'
                            validate=true>
                        </input-document>
                    </div>
                <div class="mp-checkout-pse-bank">
                    <input-select
                        name="epayco_pse[bank]"
                        label="<?= esc_html($financial_institutions_label); ?>"
                        optional="false"
                        options='<?php print_r($financial_institutions); ?>'
                        hidden-id= "hidden-financial-pse"
                        helper-message="<?= esc_html($financial_institutions_helper); ?>"
                        default-option="<?= esc_html($financial_placeholder); ?>">
                    </input-select>
                </div>


                </div>

                <!-- NOT DELETE LOADING-->
                <div id="mp-box-loading"></div>

                <!-- utilities -->
                <div id="epayco-utilities" style="display:none;">
                    <input type="hidden" id="amountPse" value="<?= esc_textarea($amount); ?>" name="epayco_pse[amount]" />
                    <input type="hidden" id="site_id" value="<?= esc_textarea($site_id); ?>" name="epayco_pse[site_id]" />
                    <input type="hidden" id="currency_ratioPse" value="<?= esc_textarea($currency_ratio); ?>" name="epayco_pse[currency_ratio]" />
                    <input type="hidden" id="campaign_idPse" name="epayco_pse[campaign_id]" />
                    <input type="hidden" id="campaignPse" name="epayco_pse[campaign]" />
                    <input type="hidden" id="discountPse" name="epayco_pse[discount]" />
                </div>

        </div>
    <?php endif; ?> 
</div>
<div>
</div>
