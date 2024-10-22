<?php

/**
 * @var bool $test_mode
 * @var string $test_mode
 * @var string $test_mode_title
 * @var string $test_mode_description
 * @var string $test_mode_link_text
 * @var string $test_mode_link_src
 * @var string $wallet_button
 * @var string $wallet_button_image
 * @var string $wallet_button_title
 * @var string $wallet_button_description
 * @var string $wallet_button_button_text
 * @var string $available_payments_title_icon
 * @var string $available_payments_title
 * @var string $available_payments_image
 * @var string $available_payments_chevron_up
 * @var string $available_payments_chevron_down
 * @var string $payment_methods_items
 * @var string $payment_methods_promotion_link
 * @var string $payment_methods_promotion_text
 * @var string $site_id
 * @var string $card_form_title
 * @var string $card_number_input_label
 * @var string $card_number_input_helper
 * @var string $card_holder_name_input_label
 * @var string $card_holder_name_input_helper
 * @var string $card_holder_email_input_label
 * @var string $card_holder_email_input_helper
 * @var string $card_holder_address_input_label
 * @var string $card_holder_address_input_helper
 * @var string $card_expiration_input_label
 * @var string $card_expiration_input_helper
 * @var string $card_security_code_input_label
 * @var string $card_security_code_input_helper
 * @var string $input_ind_phone_label
 * @var string $input_ind_phone_helper
 * @var string $card_document_input_label
 * @var string $card_document_input_helper
 * @var string $card_installments_title
 * @var string $card_issuer_input_label
 * @var string $card_installments_input_helper
 * @var string $terms_and_conditions_description
 * @var string $terms_and_conditions_link_text
 * @var string $terms_and_conditions_link_src
 * @var string $amount
 * @var string $currency_ratio
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
        <div class='mp-checkout-custom-container'>
            <?php if ($test_mode) : ?>
                <div class="mp-checkout-pro-test-mode">
                    <test-mode
                        title="<?= esc_html($test_mode_title) ?>"
                        description="<?= esc_html($test_mode_description) ?>"
                        link-text="<?= esc_html($test_mode_link_text) ?>"
                        link-src="<?= esc_html($test_mode_link_src) ?>"
                    >
                    </test-mode>
                </div>
            <?php endif; ?>


            <div id="mp-custom-checkout-form-container">

                <div class='mp-checkout-custom-card-form'>
                    <div class='mp-checkout-custom-card-row'>

                        <input-card-number
                                label-message="<?= esc_html($card_number_input_label); ?>"
                                helper-message="<?= esc_html($card_number_input_helper); ?>"
                                placeholder="0000 0000 0000 0000"
                                input-name='epayco_custom[card]'
                                flag-error='epayco_custom[cardError]'
                                validate=true
                                hidden-id= "mp-card-number-helper"
                        >
                        </input-card-number>

                    </div>

                    <div class='mp-checkout-custom-card-row mp-checkout-custom-dual-column-row'>
                        <div class='mp-checkout-custom-card-column'>
                            <input-card-expiration-date
                                    id="form-checkout__expirationDate-container"
                                    class="mp-checkout-custom-left-card-input"
                                    label-message="<?= esc_html($card_expiration_input_label); ?>"
                                    helper-message="<?= esc_html($card_expiration_input_helper); ?>"
                                    placeholder="mm/yy"
                                    input-name='epayco_custom[expirationDate]'
                                    flag-error='epayco_custom[expirationDateError]'
                                    validate=true
                                    hidden-id= "mp-expiration-date-helper"
                            >
                            </input-card-expiration-date>
                        </div>

                        <div class='mp-checkout-custom-card-column'>
                            <input-card-security-code
                                    label-message="<?= esc_html($card_security_code_input_label); ?>"
                                    helper-message="<?= esc_html($card_security_code_input_helper); ?>"
                                    placeholder=""
                                    input-name='epayco_custom[securityCode]'
                                    flag-error='epayco_custom[securityCodeError]'
                                    validate=true
                                    hidden-id= "mp-security-code-helper"
                            >
                            </input-card-security-code>
                        </div>
                    </div>

                    <div class='mp-checkout-custom-card-row' id="mp-card-holder-div">
                        <input-card-name
                                label-message="<?= esc_html($card_holder_name_input_label); ?>"
                                helper-message="<?= esc_html($card_holder_name_input_helper); ?>"
                                placeholder="Ex: John Doe"
                                input-name='epayco_custom[name]'
                                flag-error='epayco_custom[nameError]'
                                validate=true
                                hidden-id= "hidden-name-custom"
                        >
                        </input-card-name>
                    </div>

                    <div class='mp-checkout-custom-card-row' id="mp-card-holder-div">
                        <input-card-email
                                label-message="<?= esc_html($card_holder_email_input_label); ?>"
                                helper-message="<?= esc_html($card_holder_email_input_helper); ?>"
                                placeholder="jonhdoe@example.com"
                                input-name='epayco_custom[email]'
                                flag-error='epayco_custom[emailError]'
                                validate=true
                                hidden-id= "hidden-email-custom"
                        >
                        </input-card-email>
                    </div>

                    <div class='mp-checkout-custom-card-row' id="mp-card-holder-div">
                        <input-address
                                label-message="<?= esc_html($card_holder_address_input_label); ?>"
                                helper-message="<?= esc_html($card_holder_address_input_helper); ?>"
                                placeholder="Street 123"
                                input-name='epayco_custom[address]'
                                flag-error='epayco_custom[addressError]'
                                validate=true
                                hidden-id= "hidden-adress-custom"
                        >
                        </input-address>
                    </div>

                    <div class='mp-checkout-custom-card-row' id="mp-card-holder-div">
                        <input-cellphone
                                label-message="<?= esc_html($input_ind_phone_label); ?>"
                                helper-message="<?= esc_html($input_ind_phone_helper); ?>"
                                input-name='epayco_custom[cellphone]'
                                select-name='epayco_custom[cellphoneType]'
                                select-id='cellphoneType'
                                flag-error='epayco_custom[numberCellphoneError]'
                                documents='["+57","+1"]'
                                validate=true
                        >
                        </input-cellphone>
                    </div>

                    <div id="mp-doc-div" class="mp-checkout-custom-input-document">
                        <input-document
                                label-message="<?= esc_html($card_document_input_label); ?>"
                                helper-message="<?= esc_html($card_document_input_helper); ?>"
                                input-name='epayco_custom[doc_number]'
                                hidden-id="form-checkout__identificationNumber"
                                input-data-checkout="doc_number"
                                select-id="form-checkout__identificationType"
                                select-name="identificationType"
                                select-data-checkout="doc_type"
                                flag-error="docNumberError"
                                flag-error='epayco_custom[docNumberError]'
                                documents='["CC","CE","NIT","TI","PPN","SSN","LIC","DNI"]'
                                validate=true>
                        </input-document>
                    </div>



                </div>

                <div id="mp-checkout-custom-installments" class="mp-checkout-custom-installments-display-none">

                    <div id="mp-checkout-custom-issuers-container" class="mp-checkout-custom-issuers-container">
                        <div class='mp-checkout-custom-card-row'>
                            <input-label
                                isOptinal=false
                                message="<?= esc_html($card_issuer_input_label); ?>"
                                for='mp-issuer'
                            >
                            </input-label>
                        </div>

                        <div class="mp-input-select-input">
                            <select name="issuer" id="form-checkout__issuer" class="mp-input-select-select"></select>
                        </div>
                    </div>

                    <div id="mp-checkout-custom-installments-container" class="mp-checkout-custom-installments-container"></div>

                    <input-helper
                        isVisible=false
                        message="<?= esc_html($card_installments_input_helper); ?>"
                        input-id="mp-installments-helper"
                    >
                    </input-helper>

                    <select
                        style="display: none;"
                        data-checkout="installments"
                        name="installments"
                        id="form-checkout__installments"
                        class="mp-input-select-select"
                    >
                    </select>

                    <div id="mp-checkout-custom-box-input-tax-cft">
                        <div id="mp-checkout-custom-box-input-tax-tea">
                            <div id="mp-checkout-custom-tax-tea-text"></div>
                        </div>
                        <div id="mp-checkout-custom-tax-cft-text"></div>
                    </div>
                </div>
            </div>
        
        
        </div>
    <?php endif; ?>
    
</div>

<div id="epayco-utilities" style="display:none;">
    <input type="hidden" id="mp-amount" value='<?= esc_textarea($amount); ?>' name="epayco_custom[amount]"/>
    <input type="hidden" id="paymentMethodId" name="epayco_custom[payment_method_id]"/>
    <input type="hidden" id="mp_checkout_type" name="epayco_custom[checkout_type]" value="custom"/>
    <input type="hidden" id="cardTokenId" name="epayco_custom[cardTokenId]" />
    <input type="hidden" id="cardInstallments" name="epayco_custom[installments]"/>
    <input type="hidden" id="mpCardSessionId" name="epayco_custom[session_id]" />
</div>


