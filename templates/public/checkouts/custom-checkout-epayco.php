<?php
$test_mode=true;
$test_mode_title = "Test Mode";
$test_mode_description = "Use ePayco\'s payment methods without real charges.";
$test_mode_link_text = "See the rules for the test mode.";
$test_mode_link_src = "https://www.toptal.com/developers/javascript-minifier";
$card_number_input_label = "Card number";
$card_number_input_helper = "Required data";
$card_holder_name_input_label = "Holder name as it appears on the card";
$card_holder_name_input_helper = "Required data";
$card_holder_email_input_label = "Email";
$card_holder_email_input_helper = "Required data";
$card_holder_adress_input_label = "Address";
$card_holder_adress_input_helper = "Required data";
$card_expiration_input_label = "Expiration";
$card_expiration_input_helper = "Required data";
$card_security_code_input_label = "Security Code";
$card_security_code_input_helper = "Required data";
$card_document_input_label = "Holder document";
$card_document_input_helper = "Required data";
$card_installments_title = "Select the number of fees";
$card_issuer_input_label = "Fees";
$card_installments_input_helper = "Select the number of fees";

?>
<html lang="en-US" data-lt-installed="true">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="max-image-preview:large, noindex, follow">
        <title>epayco</title>
        <link rel="stylesheet" id="wc_epayco_checkout_components-css" href="./assets/css/checkouts/ep-plugins-components.min.css?ver=7.6.4" media="all">
    </head>
    <body>
        <div class="mp-checkout-custom-load">
            <div class="spinner-card-form"></div>
        </div>
        <div class='mp-checkout-container'>
            <?php if ("10000" === null) : ?>
                <p style="color: red; font-weight: bold;">
                    <?php echo $message_error_amount; ?>
                </p>
            <?php else : ?>
                <div class='mp-checkout-custom-container'>
                    <?php if ($test_mode) : ?>
                        <div class="mp-checkout-pro-test-mode">
                            <test-mode
                                    title="<?php echo $test_mode_title; ?>"
                                    description="<?php echo $test_mode_description; ?>"
                                    link-text="<?php echo $test_mode_link_text; ?>"
                                    link-src="<?php echo $test_mode_link_src; ?>"
                            >
                            </test-mode>
                        </div>
                    <?php endif; ?>



                    <div id="mp-custom-checkout-form-container">

                        <div class='mp-checkout-custom-card-form'>
                            <div class='mp-checkout-custom-card-row'>
                                <input-label
                                        isOptinal=false
                                        message="<?php echo $card_number_input_label; ?>"
                                        for='mp-card-number'
                                >
                                </input-label>

                                <div class="mp-checkout-custom-card-input" id="form-checkout__cardNumber-container"></div>

                                <input-helper
                                        isVisible=false
                                        message="<?php echo $card_number_input_helper; ?>"
                                        input-id="mp-card-number-helper"
                                >
                                </input-helper>
                            </div>

                            <div class='mp-checkout-custom-card-row mp-checkout-custom-dual-column-row'>
                                <div class='mp-checkout-custom-card-column'>
                                    <input-label
                                            message="<?php echo $card_expiration_input_label; ?>"
                                            isOptinal=false
                                    >
                                    </input-label>

                                    <div
                                            id="form-checkout__expirationDate-container"
                                            class="mp-checkout-custom-card-input mp-checkout-custom-left-card-input"
                                    >
                                    </div>

                                    <input-helper
                                            isVisible=false
                                            message="<?php echo $card_expiration_input_helper; ?>"
                                            input-id="mp-expiration-date-helper"
                                    >
                                    </input-helper>
                                </div>

                                <div class='mp-checkout-custom-card-column'>
                                    <input-label
                                            message="<?php echo $card_security_code_input_label; ?>"
                                            isOptinal=false
                                    >
                                    </input-label>

                                    <div id="form-checkout__securityCode-container" class="mp-checkout-custom-card-input"></div>

                                    <p id="mp-security-code-info" class="mp-checkout-custom-info-text"></p>

                                    <input-helper
                                            isVisible=false
                                            message="<?php echo $card_security_code_input_helper; ?>"
                                            input-id="mp-security-code-helper"
                                    >
                                    </input-helper>
                                </div>
                            </div>

                            <div class='mp-checkout-custom-card-row' id="mp-card-holder-div">
                                <input-label
                                        message="<?php echo $card_holder_name_input_label; ?>"
                                        isOptinal=false
                                >
                                </input-label>

                                <input
                                        class="mp-checkout-custom-card-input mp-card-holder-name"
                                        placeholder="Ex.: María López"
                                        id="form-checkout__cardholderName"
                                        name="mp-card-holder-name"
                                        data-checkout="cardholderName"
                                />

                                <input-helper
                                        isVisible=false
                                        message="<?php echo $card_holder_name_input_helper; ?>"
                                        input-id="mp-card-holder-name-helper"
                                        data-main="mp-card-holder-name"
                                >
                                </input-helper>
                            </div>

                            <div class='mp-checkout-custom-card-row' id="mp-card-holder-div">
                                <input-label
                                        message="<?php echo $card_holder_email_input_label; ?>"
                                        isOptinal=false
                                >
                                </input-label>

                                <input
                                        class="mp-checkout-custom-card-input mp-card-holder-email"
                                        placeholder="example@email.com"
                                        id="form-checkout__cardholderEmail"
                                        name="mp-card-holder-email"
                                        data-checkout="cardholderEmail"
                                />

                                <input-helper
                                        isVisible=false
                                        message="<?php echo $card_holder_email_input_helper; ?>"
                                        input-id="mp-card-holder-email-helper"
                                        data-main="mp-card-holder-email"
                                >
                                </input-helper>
                            </div>

                            <div class='mp-checkout-custom-card-row' id="mp-card-holder-div">
                                <input-label
                                        message="<?php echo $card_holder_adress_input_label; ?>"
                                        isOptinal=false
                                >
                                </input-label>

                                <input
                                        class="mp-checkout-custom-card-input mp-card-holder-adress"
                                        placeholder=""
                                        id="form-checkout__cardholderAdress"
                                        name="mp-card-holder-adress"
                                        data-checkout="cardholderAdress"
                                />

                                <input-helper
                                        isVisible=false
                                        message="<?php echo $card_holder_adress_input_helper; ?>"
                                        input-id="mp-card-holder-adress-helper"
                                        data-main="mp-card-holder-adress"
                                >
                                </input-helper>
                            </div>

                            <div id="mp-doc-div" class="mp-checkout-custom-input-document">
                                <input-document
                                        label-message="<?php echo $card_document_input_label; ?>"
                                        helper-message="<?php echo $card_document_input_helper; ?>"
                                        input-name="identificationNumber"
                                        hidden-id="form-checkout__identificationNumber"
                                        input-data-checkout="doc_number"
                                        select-id="form-checkout__identificationType"
                                        select-name="identificationType"
                                        select-data-checkout="doc_type"
                                        flag-error="docNumberError"
                                >
                                </input-document>
                            </div>


                            <div id="mp-adress-div" class="mp-checkout-custom-input-document">
                                <input-document
                                        label-message="<?php echo $card_document_input_label; ?>"
                                        helper-message="<?php echo $card_document_input_helper; ?>"
                                        input-name="identificationAdress"
                                        hidden-id="form-checkout__identificationAdress"
                                        input-data-checkout="doc_adress"
                                        select-id="form-checkout__identificationTypeAdress"
                                        select-name="identificationTypeAdress"
                                        select-data-checkout="adress"
                                        flag-error="adressError"
                                >
                                </input-document>
                            </div>

                        </div>

                        <div id="mp-checkout-custom-installments" class="mp-checkout-custom-installments-display-none">

                            <div id="mp-checkout-custom-issuers-container" class="mp-checkout-custom-issuers-container">
                                <div class='mp-checkout-custom-card-row'>
                                    <input-label
                                            isOptinal=false
                                            message="<?php echo $card_issuer_input_label; ?>"
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
                                    message="<?php echo $card_installments_input_helper; ?>"
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
            <input type="hidden" id="cardTokenId" name="epayco_custom[token]"/>
        </div>


        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="assets/js/checkouts/ep-plugins-components.min.js?ver=7.6.4" id="wc_epayco_checkout_components-js"></script>

    </body>
</html>


