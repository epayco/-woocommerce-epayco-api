<?php

/**
 * @var array $headerTranslations
 * @var array $credentialsTranslations
 * @var array $storeTranslations
 * @var array $gatewaysTranslations
 * @var array $testModeTranslations
 * @var string $pcustid
 * @var string $publicKey
 * @var string $privateKey
 * @var string $pKey
 * @var string $storeId
 * @var string $storeName
 * @var string $storeCategory
 * @var string $customDomain
 * @var string $customDomainOptions
 * @var string $checkboxCheckoutTestMode
 * @var string $checkboxCheckoutProductionMode
 * @var string $phpVersion
 * @var string $wcVersion
 * @var string $wpVersion
 * @var string $pluginVersion
 *
 * @var array $links
 * @var bool  $testMode
 * @var array $categories
 *
 * @var array $pluginLogs
 * @var array $allowedHtmlTags
 *
 * @see \Epayco\Woocommerce\Admin\Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<script>
    window.addEventListener("load", function() {
        mp_settings_screen_load();
    });
</script>

<span id='reference' value='{"mp-screen-name":"admin"}'></span>

<div class="mp-settings">
    <div class="mp-settings-header">
            <div class="mp-settings-header-img"></div>
        <div style="float: right">
            <div class="mp-settings-header-logo"></div>
        <hr class="mp-settings-header-hr" />
        <p class="mp-settings-header-title"><?= wp_kses($headerTranslations['title_header'], $allowedHtmlTags) ?></p>
        </div>
    </div>

    <hr class="mp-settings-hr" />

    <div class="mp-settings-credentials">
        <div id="mp-settings-step-one" class="mp-settings-title-align">
            <div class="mp-settings-title-container">
                <span class="mp-settings-font-color mp-settings-title-blocks mp-settings-margin-right">
                    <?= wp_kses($credentialsTranslations['title_credentials'], $allowedHtmlTags) ?>
                </span>
                <img class="mp-settings-margin-left mp-settings-margin-right" id="mp-settings-icon-credentials">
            </div>
            <div class="mp-settings-title-container mp-settings-margin-left">
                <img class="mp-settings-icon-open" id="mp-credentials-arrow-up">
            </div>
        </div>

        <div id="mp-step-1" class="mp-settings-block-align-top" style="display: none;">
            <div>
                <p class="mp-settings-subtitle-font-size mp-settings-title-color">
                    <?= wp_kses($credentialsTranslations['first_text_subtitle_credentials'], $allowedHtmlTags) ?>
                    <a id="mp-get-credentials-link" class="mp-settings-blue-text" target="_blank" href="<?= wp_kses($links['epayco_credentials'], $allowedHtmlTags) ?>">
                        <?= wp_kses($credentialsTranslations['text_link_credentials'], $allowedHtmlTags) ?>
                    </a>
                    <?= wp_kses($credentialsTranslations['second_text_subtitle_credentials'], $allowedHtmlTags) ?>
                </p>
            </div>
            <div class="mp-message-credentials"></div>

            <div id="msg-info-credentials"></div>

            <div class="mp-container">
                <div class="mp-block mp-block-flex mp-settings-margin-right" hidden="true">
                    <fieldset class="mp-settings-fieldset">
                        <input type="text" id="mp-public-key-prod" class="mp-settings-input" value="" placeholder="" />
                    </fieldset>
                    <fieldset>
                        <input type="text" id="mp-access-token-prod" class="mp-settings-input" value="" placeholder="" />
                    </fieldset>
                </div>

                <div class="mp-block mp-block-flex mp-settings-margin-right" hidden="true">
                    <fieldset class="mp-settings-fieldset">
                        <input type="text" id="mp-public-key-test" class="mp-settings-input" value="" placeholder="" />
                    </fieldset>

                    <fieldset>
                        <input type="text" id="mp-access-token-test" class="mp-settings-input" value="" placeholder="" />
                    </fieldset>
                </div>

                <div id="credentials-setup" class="mp-block mp-block-flex mp-settings-margin-right">
                    <p class="mp-settings-title-font-size">
                        <b><?= wp_kses($credentialsTranslations['title_credential'], $allowedHtmlTags) ?></b>
                    </p>
                    <fieldset class="mp-settings-fieldset">
                        <label for="mp-p_cust_id" class="mp-settings-label mp-settings-font-color">
                            <?= wp_kses($credentialsTranslations['p_cust_id'], $allowedHtmlTags) ?> <span style="color: red;">&nbsp;*</span>
                        </label>
                        <input type="text" id="mp-p_cust_id" class="mp-settings-input" value="<?= wp_kses($pcustid, $allowedHtmlTags) ?>" placeholder="<?= wp_kses($credentialsTranslations['placeholder_p_cust_id'], $allowedHtmlTags) ?>" />
                    </fieldset>

                    <fieldset class="mp-settings-fieldset">
                        <label for="mp-p_key" class="mp-settings-label mp-settings-font-color">
                            <?= wp_kses($credentialsTranslations['p_key'], $allowedHtmlTags) ?> <span style="color: red;">&nbsp;*</span>
                        </label>
                        <input type="text" id="mp-p_key" class="mp-settings-input" value="<?= wp_kses($pKey, $allowedHtmlTags) ?>" placeholder="<?= wp_kses($credentialsTranslations['placeholder_p_key'], $allowedHtmlTags) ?>" />
                    </fieldset>

                    <fieldset class="mp-settings-fieldset">
                        <label for="mp-publicKey" class="mp-settings-label mp-settings-font-color">
                            <?= wp_kses($credentialsTranslations['publicKey'], $allowedHtmlTags) ?> <span style="color: red;">&nbsp;*</span>
                        </label>
                        <input type="text" id="mp-publicKey" class="mp-settings-input" value="<?= wp_kses($publicKey, $allowedHtmlTags) ?>" placeholder="<?= wp_kses($credentialsTranslations['placeholder_publicKey'], $allowedHtmlTags) ?>" />
                    </fieldset class="mp-settings-fieldset">

                    <fieldset class="mp-settings-fieldset">
                        <label for="mp-private_key" class="mp-settings-label mp-settings-font-color">
                            <?= wp_kses($credentialsTranslations['private_key'], $allowedHtmlTags) ?> <span style="color: red;">&nbsp;*</span>
                        </label>
                        <input type="text" id="mp-private_key" class="mp-settings-input" value="<?= wp_kses($privateKey, $allowedHtmlTags) ?>" placeholder="<?= wp_kses($credentialsTranslations['placeholder_private_key'], $allowedHtmlTags) ?>" />
                    </fieldset>

                </div>
                <div class="loader" id="loader"></div>
            </div>

            <button class="mp-button mp-button-large" id="mp-btn-credentials">
                <?= wp_kses($credentialsTranslations['button_credentials'], $allowedHtmlTags) ?>
            </button>
        </div>
    </div>

    <hr class="mp-settings-hr" />


    <div class="mp-settings-payment">
        <div id="mp-settings-step-three" class="mp-settings-title-align">
            <div class="mp-settings-title-container">
                <span class="mp-settings-font-color mp-settings-title-blocks mp-settings-margin-right">
                    <?= wp_kses($gatewaysTranslations['title_payments'], $allowedHtmlTags) ?>
                </span>
                <img class="mp-settings-margin-left mp-settings-margin-right" id="mp-settings-icon-payment">
            </div>

            <div class="mp-settings-title-container mp-settings-margin-left">
                <img class="mp-settings-icon-open" id="mp-payments-arrow-up" />
            </div>
        </div>
        <div id="mp-step-3" class="mp-settings-block-align-top" style="display: none;">
            <p id="mp-payment" class="mp-settings-subtitle-font-size mp-settings-title-color">
                <?= wp_kses($gatewaysTranslations['subtitle_payments'], $allowedHtmlTags) ?>
            </p>
            <button id="mp-payment-method-continue" class="mp-button mp-button-large">
                <?= wp_kses($gatewaysTranslations['button_payment'], $allowedHtmlTags) ?>
            </button>
        </div>
    </div>

    <hr class="mp-settings-hr" />

    <div class="mp-settings-mode">
        <div id="mp-settings-step-four" class="mp-settings-title-align">
            <div class="mp-settings-title-container">
                <div class="mp-align-items-center">
                    <span class="mp-settings-font-color mp-settings-title-blocks mp-settings-margin-right">
                        <?= wp_kses($testModeTranslations['title_test_mode'], $allowedHtmlTags) ?>
                    </span>
                    <div id="mp-mode-badge" class="mp-settings-margin-left mp-settings-margin-right <?= $testMode ? 'mp-settings-test-mode-alert' : 'mp-settings-prod-mode-alert' ?>">
                        <span id="mp-mode-badge-test" style="display: <?= $testMode ? 'block' : 'none' ?>">
                            <?= wp_kses($testModeTranslations['badge_test'], $allowedHtmlTags) ?>
                        </span>
                        <span id="mp-mode-badge-prod" style="display: <?= $testMode ? 'none' : 'block' ?>">
                            <?= wp_kses($testModeTranslations['badge_mode'], $allowedHtmlTags) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mp-settings-title-container mp-settings-margin-left">
                <img class="mp-settings-icon-open" id="mp-modes-arrow-up" />
            </div>
        </div>

        <div id="mp-step-4" class="mp-message-test-mode mp-settings-block-align-top" style="display: none;">
            <p class="mp-heading-test-mode mp-settings-subtitle-font-size mp-settings-title-color">
                <!--<?= wp_kses($testModeTranslations['subtitle_test_mode'], $allowedHtmlTags) ?>-->
            </p>

            <div class="mp-container">
                <div class="mp-block mp-settings-choose-mode">
                    <div>
                        <p class="mp-settings-title-font-size">
                            <!--<b><?= wp_kses($testModeTranslations['title_mode'], $allowedHtmlTags) ?></b>-->
                        </p>
                    </div>

                    <div class="mp-settings-mode-container">
                        <div class="mp-settings-mode-spacing">
                            <input type="radio" id="mp-settings-testmode-test" class="mp-settings-radio-button" name="mp-test-prod" value="yes" <?= checked($testMode) ?> />
                        </div>
                        <label for="mp-settings-testmode-test">
                            <span class="mp-settings-subtitle-font-size mp-settings-font-color">
                                <?= wp_kses($testModeTranslations['title_test'], $allowedHtmlTags) ?>
                            </span>
                            <br />
                            <span class="mp-settings-subtitle-font-size mp-settings-title-color">
                                <?= wp_kses($testModeTranslations['subtitle_test'], $allowedHtmlTags) ?>
                                <span>
                                    <!--<a id="mp-test-mode-rules-link" class="mp-settings-blue-text" target="_blank" href="<?= wp_kses($links['docs_integration_test'], $allowedHtmlTags) ?>">
                                        <?= wp_kses($testModeTranslations['subtitle_test_link'], $allowedHtmlTags) ?>
                                    </a>-->
                        </label>
                    </div>

                    <div class="mp-settings-mode-container">
                        <div class="mp-settings-mode-spacing">
                            <input type="radio" id="mp-settings-testmode-prod" class="mp-settings-radio-button" name="mp-test-prod" value="no" <?= checked(!$testMode) ?> />
                        </div>
                        <label for="mp-settings-testmode-prod">
                            <span class="mp-settings-subtitle-font-size mp-settings-font-color">
                                <?= wp_kses($testModeTranslations['title_prod'], $allowedHtmlTags) ?>
                            </span>
                            <br />
                            <span class="mp-settings-subtitle-font-size mp-settings-title-color">
                                <?= wp_kses($testModeTranslations['subtitle_prod'], $allowedHtmlTags) ?>
                            </span>
                        </label>
                    </div>

                    <div class="mp-settings-alert-payment-methods" style="display:none;">
                        <div id="mp-red-badge" class="mp-settings-alert-red"></div>
                        <div class="mp-settings-alert-payment-methods-gray">
                            <div class="mp-settings-margin-right mp-settings-mode-style">
                                <span id="mp-icon-badge-error" class="mp-settings-icon-warning"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mp-settings-alert-payment-methods">
                        <div id="mp-orange-badge" class="<?= $testMode ? 'mp-settings-alert-payment-methods-orange' : 'mp-settings-alert-payment-methods-green' ?>"></div>
                        <div class="mp-settings-alert-payment-methods-gray">
                            <div class="mp-settings-margin-right mp-settings-mode-style">
                                <span id="mp-icon-badge" class="<?= $testMode ? 'mp-settings-icon-warning' : 'mp-settings-icon-success' ?>"></span>
                            </div>

                            <div class="mp-settings-mode-warning">
                                <div class="mp-settings-margin-left">
                                    <div class="mp-settings-alert-mode-title">
                                        <span id="mp-title-helper-prod" style="display: <?= $testMode ? 'none' : 'block' ?>">
                                            <span id="mp-text-badge" class="mp-display-block"> <?= wp_kses($testModeTranslations['title_message_prod'], $allowedHtmlTags) ?></span>
                                        </span>
                                        <span id="mp-title-helper-test" style="display: <?= $testMode ? 'block' : 'none' ?>">
                                            <span id="mp-text-badge" class="mp-display-block"><?= wp_kses($testModeTranslations['title_message_test'], $allowedHtmlTags) ?></span>
                                        </span>
                                    </div>

                                    <div id="mp-helper-badge-div" class="mp-settings-alert-mode-body mp-settings-font-color">
                                        <span id="mp-helper-prod" style="display: <?= $testMode ? 'none' : 'block' ?>">
                                            <!--<?= wp_kses($testModeTranslations['subtitle_message_prod'], $allowedHtmlTags) ?>-->
                                        </span>
                                        <span id="mp-helper-test" style="display: <?= $testMode ? 'block' : 'none' ?>">
                                            <!--<span><?= wp_kses($testModeTranslations['subtitle_test_one'], $allowedHtmlTags) ?></span><br />
                                            <span><?= wp_kses($testModeTranslations['subtitle_test_two'], $allowedHtmlTags) ?></span><br />
                                            <span><?= wp_kses($testModeTranslations['subtitle_test_three'], $allowedHtmlTags) ?></span>-->
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="mp-button mp-button-large" id="mp-store-mode-save">
                <?= wp_kses($testModeTranslations['button_test_mode'], $allowedHtmlTags) ?>
            </button>
        </div>
    </div>

    <div id="mp-step-5" style="display: none;"></div>

</div>

