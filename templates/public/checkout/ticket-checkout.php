<?php

use Epayco\Woocommerce\Helpers\Template;

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
 * @var string $input_country_label
 * @var string $input_country_helper
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
 * @var string $city
 * @var string $customer_title
 * @var string $logo
 * @var string $personal_data_processing_link_text
 * @var string $personal_data_processing_link_src
 * @var string $and_the
 * @var string $icon_warning
 * @see \Epayco\Woocommerce\Gateways\TicketGateway
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class='ep-checkout-container'>
    <div class="ep-checkout-ticket-container" style="max-width: 452px;margin: auto;">
        <div class="ep-checkout-ticket-content">
            <?php if ($test_mode) : ?>
                <div class="ep-checkout-ticket-test-mode-epayco">
                    <test-mode-epayco
                            title="<?php echo esc_html($test_mode_title); ?>"
                            description="<?php echo esc_html($test_mode_description); ?>"
                            link-text="<?php echo esc_html($test_mode_link_text); ?>"
                            link-src="<?php echo esc_html($test_mode_link_src); ?>"
                            icon-src="<?php echo esc_html($icon_warning); ?>"
                    >
                    </test-mode-epayco>
                </div>
            <?php endif; ?>
            <div style="margin-top: 10px; font-weight: bold; display: flex; align-items: center;">
                <svg width="21" height="16" viewBox="0 0 21 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.013 8.528H17.7695V7.38514H13.013V8.528ZM13.013 5.36229H17.7695V4.21943H13.013V5.36229ZM3.2305 11.7806H10.9492V11.5909C10.9492 10.9242 10.6069 10.408 9.9225 10.0423C9.23806 9.67657 8.29383 9.49371 7.08983 9.49371C5.88583 9.49371 4.94122 9.67657 4.256 10.0423C3.57078 10.408 3.22894 10.9242 3.2305 11.5909V11.7806ZM7.08983 7.648C7.58217 7.648 7.99672 7.48305 8.3335 7.15314C8.67105 6.82247 8.83983 6.416 8.83983 5.93371C8.83983 5.45143 8.67105 5.04533 8.3335 4.71543C7.99594 4.38552 7.58139 4.22019 7.08983 4.21943C6.59828 4.21867 6.18372 4.384 5.84617 4.71543C5.50861 5.04686 5.33983 5.45295 5.33983 5.93371C5.33983 6.41448 5.50861 6.82095 5.84617 7.15314C6.18372 7.48533 6.59828 7.65028 7.08983 7.648ZM1.88533 16C1.34789 16 0.8995 15.824 0.540167 15.472C0.180833 15.12 0.000777778 14.6804 0 14.1531V1.84686C0 1.32038 0.180056 0.881142 0.540167 0.529143C0.900278 0.177143 1.34828 0.000761905 1.88417 0H19.1158C19.6525 0 20.1005 0.176381 20.4598 0.529143C20.8192 0.881904 20.9992 1.32114 21 1.84686V14.1543C21 14.68 20.8199 15.1192 20.4598 15.472C20.0997 15.8248 19.6517 16.0008 19.1158 16H1.88533ZM1.88533 14.8571H19.1158C19.2947 14.8571 19.4592 14.784 19.6093 14.6377C19.7594 14.4914 19.8341 14.3299 19.8333 14.1531V1.84686C19.8333 1.67086 19.7587 1.50933 19.6093 1.36229C19.46 1.21524 19.2955 1.1421 19.1158 1.14286H1.88417C1.70528 1.14286 1.54078 1.216 1.39067 1.36229C1.24056 1.50857 1.16589 1.6701 1.16667 1.84686V14.1543C1.16667 14.3295 1.24133 14.4907 1.39067 14.6377C1.54 14.7848 1.7045 14.8579 1.88417 14.8571" fill="black"/>
                </svg>
                <p style="margin-left: 10px;"><?php echo esc_html($customer_title) ?></p>
            </div>
            <div id="ep-custom-checkout-form-container" style="margin: 10px;">
                <div class="ep-checkout-ticket-input-document">
                    <input-name-epayco
                            labelMessage="<?php echo esc_html($input_name_label); ?>"
                            helperMessage="<?php echo esc_html($input_name_helper); ?>"
                            placeholder="Escribe..."
                            inputName='epayco_ticket[name]'
                            flagError='epayco_ticket[nameError]'
                            validate=true
                            hiddenId="hidden-name-ticket"
                    >
                    </input-name-epayco>
                </div>

                <div class="ep-checkout-ticket-input-document">
                    <input-email-epayco
                            labelMessage="<?php echo esc_html($input_email_label); ?>"
                            helperMessage="<?php echo esc_html($input_email_helper); ?>"
                            placeholder="Escribe..."
                            inputName='epayco_ticket[email]'
                            flagError='epayco_ticket[emailError]'
                            validate=true
                            hiddenId= "hidden-email-ticket"
                    >
                    </input-email-epayco>
                </div>

                <div class="ep-checkout-ticket-input-document">
                    <input-address
                            labelMessage="<?php echo esc_html($input_address_label); ?>"
                            helperMessage="<?php echo esc_html($input_address_helper); ?>"
                            placeholder="Escribe..."
                            inputName='epayco_ticket[address]'
                            flagError='epayco_ticket[addressError]'
                            validate=true
                            hiddenId= "hidden-address-ticket"
                    >
                    </input-address>
                </div>

                <div class="ep-checkout-ticket-input-document">
                    <input-cellphone-epayco
                            label-message="<?php echo esc_html($input_ind_phone_label); ?>"
                            helper-message="<?php echo esc_html($input_ind_phone_helper); ?>"
                            input-name-epayco='epayco_ticket[cellphone]'
                            hidden-id="cellphoneType"
                            input-data-checkout="cellphone_number"
                            select-id="cellphoneType"
                            input-id="cellphoneTypeNumber"
                            select-name="epayco_ticket[cellphoneType]"
                            select-data-checkout="cellphone_type"
                            flag-error="cellphoneTypeError"
                            validate=true
                            placeholder="Número de celular"
                    >
                    </input-cellphone-epayco>
                </div>

                <div class="ep-checkout-ticket-input-document">
                    <input-document-epayco
                            label-message="<?php echo esc_html($input_document_label); ?>"
                            helper-message="<?php echo esc_html($input_document_helper); ?>"
                            input-name-epayco='epayco_ticket[document]'
                            hidden-id="documentType"
                            input-data-checkout="document_number"
                            select-id="documentType"
                            input-id="documentTypeNumber"
                            select-name="epayco_ticket[documentType]"
                            select-data-checkout="document_type"
                            flag-error="documentTypeError"
                            documents='[
                                    {"id":"Tipo"},
                                    {"id":"CC"},
                                    {"id":"NIT"},
                                    {"id":"CE"},
                                    {"id":"PPN"},
                                    {"id":"SSN"},
                                    {"id":"LIC"},
                                    {"id":"DNI"},
                                    {"id":"PEP"},
                                    {"id":"PPT"}
                                ]'
                            validate=true
                            placeholder="Número de documento"
                    >
                    </input-document-epayco>
                </div>

                <div class="ep-checkout-ticket-input-document">
                    <input-country-epayco
                            label-message="<?php echo esc_html($input_country_label); ?>"
                            helper-message="<?php echo esc_html($input_country_helper); ?>"
                            input-name-epayco='epayco_ticket[country]'
                            hidden-id="countryType"
                            input-data-checkout="country_number"
                            select-id="countryType"
                            input-id="countryTypeNumber"
                            select-name="epayco_ticket[countryType]"
                            select-data-checkout="doc_type"
                            flag-error="countryTypeError"
                            validate=true
                            placeholder="<?php echo esc_html($city); ?>"
                    >
                    </input-country-epayco>
                </div>


            </div>
            <hr style="margin: 24px auto;"/>
            <div style="margin-top: 10px; font-weight: bold; display: flex; align-items: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 17V7C2 6.46957 2.21071 5.96086 2.58579 5.58579C2.96086 5.21071 3.46957 5 4 5H20C20.5304 5 21.0391 5.21071 21.4142 5.58579C21.7893 5.96086 22 6.46957 22 7V17C22 17.5304 21.7893 18.0391 21.4142 18.4142C21.0391 18.7893 20.5304 19 20 19H4C3.46957 19 2.96086 18.7893 2.58579 18.4142C2.21071 18.0391 2 17.5304 2 17Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.5 12.01L18.51 11.999M5.5 12.01L5.51 11.999M12 15C11.2044 15 10.4413 14.6839 9.87868 14.1213C9.31607 13.5587 9 12.7956 9 12C9 11.2044 9.31607 10.4413 9.87868 9.87868C10.4413 9.31607 11.2044 9 12 9C12.7956 9 13.5587 9.31607 14.1213 9.87868C14.6839 10.4413 15 11.2044 15 12C15 12.7956 14.6839 13.5587 14.1213 14.1213C13.5587 14.6839 12.7956 15 12 15Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p style="margin-left: 10px;"><?php echo esc_html($ticket_text_label) ?></p>
            </div>
            <div id="ep-custom-checkout-form-container" style="margin: 10px;">
                <div class="ep-checkout-ticket-payment-method">
                    <!--<p class="ep-checkout-ticket-text" data-cy="checkout-ticket-text">
                            <?php echo esc_html($ticket_text_label); ?>
                        </p>-->

                    <input-table-epayco
                            name="epayco_ticket[payment_method_id]"
                            button-name=<?php echo esc_html($input_table_button); ?>
                            columns='<?php echo esc_attr(wp_json_encode($payment_methods)); ?>'>
                    </input-table-epayco>

                    <input-helper-epayco
                            isVisible=false
                            message="<?php echo esc_html($input_helper_label); ?>"
                            input-id="ep-payment-method-helper"
                            id="payment-method-helper">
                    </input-helper-epayco>
                </div>
                <!-- NOT DELETE LOADING-->
                <div id="ep-box-loading"></div>

                <!-- utilities -->
                <div id="epayco-utilities" style="display:none;">
                    <input type="hidden" id="site_id" value="<?php echo esc_textarea($site_id); ?>" name="epayco_ticket[site_id]" />
                </div>
            </div>
        </div>

        <div class="ep-checkout-ticket-terms-and-conditions">
            <terms-and-conditions
                    label="<?php echo esc_html($terms_and_conditions_label); ?>"
                    description="<?php echo esc_html($terms_and_conditions_description); ?>"
                    link-text="<?php echo esc_html($terms_and_conditions_link_text); ?>"
                    link-src="<?php echo esc_html($terms_and_conditions_link_src); ?>"
                    link-condiction-text="<?php echo esc_html($personal_data_processing_link_text); ?>"
                    and_the="<?php echo esc_html($and_the); ?>"
                    link-condiction-src="<?php echo esc_html($personal_data_processing_link_src); ?>"
            >
            </terms-and-conditions>
        </div>
        <div style="display: flex;justify-content: center; align-items: center;padding: 15px;">
            <p>Secured by</p>
            <img width="65px" src="<?php echo esc_html($logo); ?>">
        </div>
    </div>
</div>

