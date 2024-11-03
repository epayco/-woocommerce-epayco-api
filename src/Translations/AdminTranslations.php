<?php

namespace Epayco\Woocommerce\Translations;

use Epayco\Woocommerce\Helpers\Links;

if (!defined('ABSPATH')) {
    exit;
}

class AdminTranslations
{
    /**
     * @var array
     */
    public $notices = [];

    /**
     * @var array
     */
    public $plugin = [];

    /**
     * @var array
     */
    public $order = [];

    /**
     * @var array
     */
    public $headerSettings = [];

    /**
     * @var array
     */
    public $credentialsSettings = [];


    /**
     * @var array
     */
    public $storeSettings = [];

    /**
     * @var array
     */
    public $gatewaysSettings = [];

    /**
     * @var array
     */
    public $basicGatewaySettings = [];

    /**
     * @var array
     */
    public $creditcardGatewaySettings = [];


    /**
     * @var array
     */
    public $subscriptionGatewaySettings = [];

    /**
     * @var array
     */
    public $ticketGatewaySettings = [];

    /**
     * @var array
     */
    public $pseGatewaySettings = [];

    /**
     * @var array
     */
    public $checkoutGatewaySettings = [];

    /**
     * @var array
     */
    public $daviplatatewaySettings = [];

    /**
     * @var array
     */
    public $testModeSettings = [];

    /**
     * @var array
     */
    public $configurationTips = [];

    /**
     * @var array
     */
    public $validateCredentials = [];

    /**
     * @var array
     */
    public $updateCredentials = [];

    /**
     * @var array
     */
    public $updateStore = [];

    /**
     * @var array
     */
    public $currency = [];

    /**
     * @var array
     */
    public $statusSync = [];

    /**
     * @var array
     */
    public $links;

    /**
     * Translations constructor
     *
     * @param Links $links
     */
    public function __construct(Links $links)
    {
        $this->links = $links->getLinks();

        $this->setNoticesTranslations();
        $this->setPluginSettingsTranslations();
        $this->setHeaderSettingsTranslations();
        $this->setCredentialsSettingsTranslations();
        $this->setStoreSettingsTranslations();
        $this->setOrderSettingsTranslations();
        $this->setGatewaysSettingsTranslations();
        $this->setBasicGatewaySettingsTranslations();
        $this->setcreditCardGatewaySettingsTranslations ();
        $this->setSubscriptonGatewaySettingsTranslations();
        $this->setTicketGatewaySettingsTranslations();
        $this->setPseGatewaySettingsTranslations();
        $this->setCheckoutGatewaySettingsTranslations();
        $this->setDaviplataGatewaySettingsTranslations();
        $this->setTestModeSettingsTranslations();
        $this->setConfigurationTipsTranslations();
        $this->setUpdateCredentialsTranslations();
        $this->setValidateCredentialsTranslations();
        $this->setUpdateStoreTranslations();
        $this->setCurrencyTranslations();
        $this->setStatusSyncTranslations();
    }

    /**
     * Set notices translations
     *
     * @return void
     */
    private function setNoticesTranslations(): void
    {
        $missWoocommerce = sprintf(
            __('The ePayco module needs an active version of %s in order to work!', 'woocommerce-epayco'),
            '<a target="_blank" href="https://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>'
        );

        $this->notices = [
            'miss_woocommerce'          => $missWoocommerce,
            'php_wrong_version'         => __('ePayco payments for WooCommerce requires PHP version 7.4 or later. Please update your PHP version.', 'woocommerce-epayco'),
            'missing_curl'              => __('ePayco Error: PHP Extension CURL is not installed.', 'woocommerce-epayco'),
            'missing_gd_extensions'     => __('ePayco Error: PHP Extension GD is not installed. Installation of GD extension is required to send QR Code Pix by email.', 'woocommerce-epayco'),
            'activate_woocommerce'      => __('Activate WooCommerce', 'woocommerce-epayco'),
            'install_woocommerce'       => __('Install WooCommerce', 'woocommerce-epayco'),
            'see_woocommerce'           => __('See WooCommerce', 'woocommerce-epayco'),
            'miss_pix_text'             => __('Please note that to receive payments via Pix at our checkout, you must have a Pix key registered in your Sdk account.', 'woocommerce-epayco'),
            'miss_pix_link'             => __('Register your Pix key at Sdk.', 'woocommerce-epayco'),
            'dismissed_review_title'    => sprintf(__('%s, help us improve the experience we offer', 'woocommerce-epayco'), wp_get_current_user()->display_name),
            'dismissed_review_subtitle' => __('Share your opinion with us so that we improve our product and offer the best payment solution.', 'woocommerce-epayco'),
            'dismissed_review_button'   => __('Rate the plugin', 'woocommerce-epayco'),
            'saved_cards_title'         => __('Enable payments via Sdk account', 'woocommerce-epayco'),
            'saved_cards_subtitle'      => __('When you enable this function, your customers pay faster using their Sdk accounts.</br>The approval rate of these payments in your store can be 25% higher compared to other payment methods.', 'woocommerce-epayco'),
            'saved_cards_button'        => __('Activate', 'woocommerce-epayco'),
            'missing_translation'       => __("Our plugin does not support the language you've chosen, so we've switched it to the English default. If you prefer, you can also select Spanish or Portuguese (Brazilian).", 'woocommerce-epayco'),
            'action_feedback_title'     => __('You activated Sdk’s plug-in', 'woocommerce-epayco'),
            'action_feedback_subtitle'  => __('Follow the instructions below to integrate your store with Sdk and start to sell.', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set plugin settings translations
     *
     * @return void
     */
    private function setPluginSettingsTranslations(): void
    {
        $this->plugin = [
            'set_plugin'     => __('Set plugin', 'woocommerce-epayco'),
            'payment_method' => __('Payment methods', 'woocommerce-epayco')
        ];
    }

    /**
     * Set order settings translations
     *
     * @return void
     */
    private function setOrderSettingsTranslations(): void
    {
        $this->order = [
            'cancel_order'                       => __('Cancel order', 'woocommerce-epayco'),
            'order_note_commission_title'        => __('ePayco commission:', 'woocommerce-epayco'),
            'order_note_commission_tip'          => __('Represents the commission configured on plugin settings.', 'woocommerce-epayco'),
            'order_note_discount_title'          => __('ePayco discount:', 'woocommerce-epayco'),
            'order_note_discount_tip'            => __('Represents the discount configured on plugin settings.', 'woocommerce-epayco'),
            'order_note_installments_fee_tip'    => __('Represents the installment fee charged by Sdk.', 'woocommerce-epayco'),
            'order_note_installments_fee_title'  => __('ePayco Installment Fee:', 'woocommerce-epayco'),
            'order_note_total_paid_amount_tip'   => __('Represents the total purchase plus the installment fee charged by Sdk.', 'woocommerce-epayco'),
            'order_note_total_paid_amount_title' => __('ePayco Total:', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set headers settings translations
     *
     * @return void
     */
    private function setHeaderSettingsTranslations(): void
    {
        $titleHeader = sprintf(
            '%s %s %s <br/> %s %s',
            __('Accept', 'woocommerce-epayco'),
            __('payments', 'woocommerce-epayco'),
            __('safely', 'woocommerce-epayco'),
            __('with', 'woocommerce-epayco'),
            __('ePayco', 'woocommerce-epayco')
        );

        $installmentsDescription = sprintf(
            '%s <b>%s</b> %s <b>%s</b> %s',
            __('Choose', 'woocommerce-epayco'),
            __('when you want to receive the money', 'woocommerce-epayco'),
            __('from your sales and if you want to offer', 'woocommerce-epayco'),
            __('interest-free installments', 'woocommerce-epayco'),
            __('to your clients.', 'woocommerce-epayco')
        );



        $this->headerSettings = [
            'ssl'                      => __('SSL', 'woocommerce-epayco'),
            'curl'                     => __('Curl', 'woocommerce-epayco'),
            'gd_extension'             => __('GD Extensions', 'woocommerce-epayco'),
            'title_header'             => $titleHeader,
            'title_requirements'       => __('Technical requirements', 'woocommerce-epayco'),
            'title_installments'       => __('Collections and installments', 'woocommerce-epayco'),
            'title_questions'          => __('More information', 'woocommerce-epayco'),
            'description_ssl'          => __('Implementation responsible for transmitting data to ePayco in a secure and encrypted way.', 'woocommerce-epayco'),
            'description_curl'         => __('It is an extension responsible for making payments via requests from the plugin to ePayco.', 'woocommerce-epayco'),
            'description_gd_extension' => __('These extensions are responsible for the implementation and operation of Pix in your store.', 'woocommerce-epayco'),
            'description_installments' => $installmentsDescription,
            'description_questions'    => __('Check our documentation to learn more about integrating our plug-in.', 'woocommerce-epayco'),
            'button_installments'      => __('Set deadlines and fees', 'woocommerce-epayco'),
            'button_questions'         => __('Go to documentation', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set credentials settings translations
     *
     * @return void
     */
    private function setCredentialsSettingsTranslations(): void
    {

        $this->credentialsSettings = [
            'title_credentials'                 => __('1. Enter your credentials to integrate your store with ePayco', 'woocommerce-epayco'),
            'title_credential'                  => __('Credentials', 'woocommerce-epayco'),
            'first_text_subtitle_credentials'   => __('To start selling, ', 'woocommerce-epayco'),
            'second_text_subtitle_credentials'  => __('in the fields below. If you don’t have credentials yet, you’ll have to create them from this link.', 'woocommerce-epayco'),
            'p_cust_id'                         => __('P_CUST_ID_CLIENTE', 'woocommerce-epayco'),
            'publicKey'                         => __('PUBLIC_KEY', 'woocommerce-epayco'),
            'private_key'                       => __('PRIVATE_KEY', 'woocommerce-epayco'),
            'p_key'                             => __('P_KEY', 'woocommerce-epayco'),
            'placeholder_p_cust_id'             => __('Paste your P_CUST_ID here', 'woocommerce-epayco'),
            'placeholder_publicKey'             => __('Paste your PUBLIC_KEY here', 'woocommerce-epayco'),
            'placeholder_private_key'           => __('Paste your PRIVATE_KEY here', 'woocommerce-epayco'),
            'placeholder_p_key'                 => __('Paste your P_KEY here', 'woocommerce-epayco'),
            'button_credentials'                => __('Save and continue', 'woocommerce-epayco'),
            'text_link_credentials'             => __('copy and paste your credentials ', 'woocommerce-epayco')
        ];
    }

    /**
     * Set store settings translations
     *
     * @return void
     */
    private function setStoreSettingsTranslations(): void
    {
        $helperUrl = sprintf(
            '%s %s <a class="ep-settings-blue-text" target="_blank" href="%s">%s</a>.',
            __('Add the URL to receive payments notifications.', 'woocommerce-epayco'),
            __('Find out more information in the', 'woocommerce-epayco'),
            $this->links['docs_ipn_notification'],
            __('guides', 'woocommerce-epayco')
        );

        $helperIntegrator = sprintf(
            '%s %s <a class="ep-settings-blue-text" target="_blank" href="%s">%s</a>.',
            __('If you are a ePayco Certified Partner, make sure to add your integrator_id.', 'woocommerce-epayco'),
            __('If you do not have the code, please', 'woocommerce-epayco'),
            $this->links['docs_developers_program'],
            __('request it now', 'woocommerce-epayco')
        );

        $this->storeSettings = [
            'title_store'                   => __('2. Customize your business’ information', 'woocommerce-epayco'),
            'title_info_store'              => __('Your store information', 'woocommerce-epayco'),
            'title_advanced_store'          => __('Advanced integration options (optional)', 'woocommerce-epayco'),
            'title_debug'                   => __('Debug and Log Mode', 'woocommerce-epayco'),
            'subtitle_store'                => __('Fill out the following details to have a better experience and offer your customers more information.', 'woocommerce-epayco'),
            'subtitle_name_store'           => __('Name of your store in your client\'s invoice', 'woocommerce-epayco'),
            'subtitle_activities_store'     => __('Identification in Activities of Sdk', 'woocommerce-epayco'),
            'subtitle_advanced_store'       => __('For further integration of your store with Sdk (IPN, Certified Partners, Debug Mode)', 'woocommerce-epayco'),
            'subtitle_category_store'       => __('Store category', 'woocommerce-epayco'),
            'subtitle_url'                  => __('URL for IPN', 'woocommerce-epayco'),
            'subtitle_integrator'           => __('Integrator ID', 'woocommerce-epayco'),
            'subtitle_debug'                => __('We record your store\'s actions in order to provide a better assistance.', 'woocommerce-epayco'),
            'placeholder_name_store'        => __('Ex: Mary\'s Store', 'woocommerce-epayco'),
            'placeholder_activities_store'  => __('Ex: Mary Store', 'woocommerce-epayco'),
            'placeholder_category_store'    => __('Select', 'woocommerce-epayco'),
            'placeholder_url'               => __('Ex: https://examples.com/my-custom-ipn-url', 'woocommerce-epayco'),
            'options_url'                   => __('Add plugin default params', 'woocommerce-epayco'),
            'placeholder_integrator'        => __('Ex: 14987126498', 'woocommerce-epayco'),
            'accordion_advanced_store_show' => __('Show advanced options', 'woocommerce-epayco'),
            'accordion_advanced_store_hide' => __('Hide advanced options', 'woocommerce-epayco'),
            'button_store'                  => __('Save and continue', 'woocommerce-epayco'),
            'helper_name_store'             => __('If this field is empty, the purchase will be identified as Sdk.', 'woocommerce-epayco'),
            'helper_activities_store'       => __('In Activities, you will view this term before the order number', 'woocommerce-epayco'),
            'helper_category_store'         => __('Select "Other categories" if you do not find the appropriate category.', 'woocommerce-epayco'),
            'helper_integrator_link'        => __('request it now.', 'woocommerce-epayco'),
            'helper_url'                    => $helperUrl,
            'helper_integrator'             => $helperIntegrator,
            'title_cron_config'             => __('Order tracking', 'woocommerce-epayco'),
            'subtitle_cron_config'          => __('We will keep your Sdk orders updated every hour. We recommend activating this option only in the event of automatic order update failures.', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set gateway settings translations
     *
     * @return void
     */
    private function setGatewaysSettingsTranslations(): void
    {
        $this->gatewaysSettings = [
            'title_payments'    => __('2. Activate and set up payment methods', 'woocommerce-epayco'),
            'subtitle_payments' => __('Select the payment method you want to appear in your store to activate and set it up.', 'woocommerce-epayco'),
            'settings_payment'  => __('Settings', 'woocommerce-epayco'),
            'button_payment'    => __('Continue', 'woocommerce-epayco'),
            'enabled'           => __('Enabled', 'woocommerce-epayco'),
            'disabled'          => __('Disabled', 'woocommerce-epayco'),
            'empty_credentials' => __('Configure your credentials to enable ePayco payment methods.', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set basic settings translations
     *
     * @return void
     */
    private function setBasicGatewaySettingsTranslations(): void
    {
        $enabledDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('The checkout is', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $enabledDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('The checkout is', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $currencyConversionDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $currencyConversionDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $autoReturnDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('The buyer', 'woocommerce-epayco'),
            __('will be automatically redirected to the store', 'woocommerce-epayco')
        );

        $autoReturnDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('The buyer', 'woocommerce-epayco'),
            __('will not be automatically redirected to the store', 'woocommerce-epayco')
        );


        $binaryModeDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Pending payments', 'woocommerce-epayco'),
            __('will be automatically declined', 'woocommerce-epayco')
        );

        $binaryModeDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Pending payments', 'woocommerce-epayco'),
            __('will not be automatically declined', 'woocommerce-epayco')
        );

        $this->basicGatewaySettings = [
            'gateway_title'                             => __('Checkout ePayco', 'woocommerce-epayco'),
            'gateway_description'                       => __('Your clients finalize their payments in ePayco.', 'woocommerce-epayco'),
            'gateway_method_title'                      => __('ePayco - Checkout Pro', 'woocommerce-epayco'),
            'gateway_method_description'                => __('Your clients finalize their payments in ePayco.', 'woocommerce-epayco'),
            'header_title'                              => __('Checkout Pro', 'woocommerce-epayco'),
            'header_description'                        => __('With Checkout Pro you sell with all the safety inside Sdk environment.', 'woocommerce-epayco'),
            'card_settings_title'                       => __('ePayco plugin general settings', 'woocommerce-epayco'),
            'card_settings_subtitle'                    => __('Set the deadlines and fees, test your store or access the Plugin manual.', 'woocommerce-epayco'),
            'card_settings_button_text'                 => __('Go to Settings', 'woocommerce-epayco'),
            'enabled_title'                             => __('Enable the checkout', 'woocommerce-epayco'),
            'enabled_subtitle'                          => __('By disabling it, you will disable all payments from Sdk Checkout at Sdk website by redirect.', 'woocommerce-epayco'),
            'enabled_descriptions_enabled'              => $enabledDescriptionsEnabled,
            'enabled_descriptions_disabled'             => $enabledDescriptionsDisabled,
            'title_title'                               => __('Title in the store Checkout', 'woocommerce-epayco'),
            'title_description'                         => __('Change the display text in Checkout, maximum characters: 85', 'woocommerce-epayco'),
            'title_default'                             => __('Checkout ePayco', 'woocommerce-epayco'),
            'title_desc_tip'                            => __('The text inserted here will not be translated to other languages', 'woocommerce-epayco'),
            'currency_conversion_title'                 => __('Convert Currency', 'woocommerce-epayco'),
            'currency_conversion_subtitle'              => __('Activate this option so that the value of the currency set in WooCommerce is compatible with the value of the currency you use in Sdk.', 'woocommerce-epayco'),
            'currency_conversion_descriptions_enabled'  => $currencyConversionDescriptionsEnabled,
            'currency_conversion_descriptions_disabled' => $currencyConversionDescriptionsDisabled,
            'ex_payments_title'                         => __('Choose the payment methods you accept in your store', 'woocommerce-epayco'),
            'ex_payments_description'                   => __('Enable the payment methods available to your clients.', 'woocommerce-epayco'),
            'ex_payments_type_credit_card_label'        => __('Credit Cards', 'woocommerce-epayco'),
            'ex_payments_type_debit_card_label'         => __('Debit Cards', 'woocommerce-epayco'),
            'ex_payments_type_other_label'              => __('Other Payment Methods', 'woocommerce-epayco'),
            'installments_title'                        => __('Maximum number of installments', 'woocommerce-epayco'),
            'installments_description'                  => __('What is the maximum quota with which a customer can buy?', 'woocommerce-epayco'),
            'installments_options_1'                    => __('1 installment', 'woocommerce-epayco'),
            'installments_options_2'                    => __('2 installments', 'woocommerce-epayco'),
            'installments_options_3'                    => __('3 installments', 'woocommerce-epayco'),
            'installments_options_4'                    => __('4 installments', 'woocommerce-epayco'),
            'installments_options_5'                    => __('5 installments', 'woocommerce-epayco'),
            'installments_options_6'                    => __('6 installments', 'woocommerce-epayco'),
            'installments_options_10'                   => __('10 installments', 'woocommerce-epayco'),
            'installments_options_12'                   => __('12 installments', 'woocommerce-epayco'),
            'installments_options_15'                   => __('15 installments', 'woocommerce-epayco'),
            'installments_options_18'                   => __('18 installments', 'woocommerce-epayco'),
            'installments_options_24'                   => __('24 installments', 'woocommerce-epayco'),
            'advanced_configuration_title'              => __('Advanced settings', 'woocommerce-epayco'),
            'advanced_configuration_description'        => __('Edit these advanced fields only when you want to modify the preset values.', 'woocommerce-epayco'),
            'method_title'                              => __('Payment experience', 'woocommerce-epayco'),
            'method_description'                        => __('Define what payment experience your customers will have, whether inside or outside your store.', 'woocommerce-epayco'),
            'method_options_redirect'                   => __('Redirect', 'woocommerce-epayco'),
            'method_options_modal'                      => __('Modal', 'woocommerce-epayco'),
            'auto_return_title'                         => __('Return to the store', 'woocommerce-epayco'),
            'auto_return_subtitle'                      => __('Do you want your customer to automatically return to the store after payment?', 'woocommerce-epayco'),
            'auto_return_descriptions_enabled'          => $autoReturnDescriptionsEnabled,
            'auto_return_descriptions_disabled'         => $autoReturnDescriptionsDisabled,
            'success_url_title'                         => __('Success URL', 'woocommerce-epayco'),
            'success_url_description'                   => __('Choose the URL that we will show your customers when they finish their purchase.', 'woocommerce-epayco'),
            'failure_url_title'                         => __('Payment URL rejected', 'woocommerce-epayco'),
            'failure_url_description'                   => __('Choose the URL that we will show to your customers when we refuse their purchase. Make sure it includes a message appropriate to the situation and give them useful information so they can solve it.', 'woocommerce-epayco'),
            'pending_url_title'                         => __('Payment URL pending', 'woocommerce-epayco'),
            'pending_url_description'                   => __('Choose the URL that we will show to your customers when they have a payment pending approval.', 'woocommerce-epayco'),
            'binary_mode_title'                         => __('Automatic decline of payments without instant approval', 'woocommerce-epayco'),
            'binary_mode_subtitle'                      => __('Enable it if you want to automatically decline payments that are not instantly approved by banks or other institutions.', 'woocommerce-epayco'),
            'binary_mode_default'                       => __('Debit, Credit and Invoice in Sdk environment.', 'woocommerce-epayco'),
            'binary_mode_descriptions_enabled'          => $binaryModeDescriptionsEnabled,
            'binary_mode_descriptions_disabled'         => $binaryModeDescriptionsDisabled,
            'discount_title'                            => __('Discount in Sdk Checkouts', 'woocommerce-epayco'),
            'discount_description'                      => __('Choose a percentage value that you want to discount your customers for paying with Sdk.', 'woocommerce-epayco'),
            'discount_checkbox_label'                   => __('Activate and show this information on Sdk Checkout', 'woocommerce-epayco'),
            'commission_title'                          => __('Commission in Sdk Checkouts', 'woocommerce-epayco'),
            'commission_description'                    => __('Choose an additional percentage value that you want to charge as commission to your customers for paying with Sdk.', 'woocommerce-epayco'),
            'commission_checkbox_label'                 => __('Activate and show this information on Sdk Checkout', 'woocommerce-epayco'),
            'invalid_back_url'                          => __('This seems to be an invalid URL', 'woocommerce-epayco'),
        ];
        $this->basicGatewaySettings  = array_merge($this->basicGatewaySettings, $this->setSupportLinkTranslations());
    }

    /**
     * Set credits settings translations
     *
     * @return void
     */
    private function setcreditCardGatewaySettingsTranslations (): void
    {
        $enabledDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Credit cards is', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $enabledDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Credit cards is', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $this->creditcardGatewaySettings = [
            'gateway_title'                             => __('Credit and debit cards', 'woocommerce-epayco'),
            'gateway_description'                       => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'gateway_method_title'                      => __('ePayco - Checkout API', 'woocommerce-epayco'),
            'gateway_method_description'                => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'header_title'                              => __('Credit card', 'woocommerce-epayco'),
            'header_description'                        => __('With the Credit card payment, you can sell inside your store environment, without redirection and with the security from ePayco.', 'woocommerce-epayco'),
            'enabled_title'                             => __('Enable', 'woocommerce-epayco'),
            'enabled_subtitle'                          => __('By disabling it, you will disable all credit cards payments from ePayco.', 'woocommerce-epayco'),
            'enabled_descriptions_enabled'              => $enabledDescriptionsEnabled,
            'enabled_descriptions_disabled'             => $enabledDescriptionsDisabled,
            'title_title'                               => __('Title in the store Checkout', 'woocommerce-epayco'),
            'title_description'                         => __('Change the display text in Checkout, maximum characters: 85', 'woocommerce-epayco'),
            'title_default'                             => __('Credit and debit cards', 'woocommerce-epayco'),
            'title_desc_tip'                            => __('The text inserted here will not be translated to other languages', 'woocommerce-epayco')
        ];
        $this->creditcardGatewaySettings  = array_merge($this->creditcardGatewaySettings, $this->setSupportLinkTranslations());
    }



    /**
     * Set credits settings translations
     *
     * @return void
     */
    private function setSubscriptonGatewaySettingsTranslations(): void
    {
        $enabledDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Subscription', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $enabledDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Subscription', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $currencyConversionDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $currencyConversionDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $walletButtonDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Payments via ePayco accounts are', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $walletButtonDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Payments via ePayco accounts are', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $binaryModeDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Pending payments', 'woocommerce-epayco'),
            __('will be automatically declined', 'woocommerce-epayco')
        );

        $binaryModeDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Pending payments', 'woocommerce-epayco'),
            __('will not be automatically declined', 'woocommerce-epayco')
        );

        $this->subscriptionGatewaySettings = [
            'gateway_title'                             => __('Subscription', 'woocommerce-epayco'),
            'gateway_description'                       => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'gateway_method_title'                      => __('ePayco - Checkout API', 'woocommerce-epayco'),
            'gateway_method_description'                => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'header_title'                              => __('Subscription', 'woocommerce-epayco'),
            'header_description'                        => __('With the Subscription payment, you can sell inside your store environment, without redirection and with the security from ePayco.', 'woocommerce-epayco'),
            'card_settings_title'                       => __('Sdk Plugin general settings', 'woocommerce-epayco'),
            'card_settings_subtitle'                    => __('Set the deadlines and fees, test your store or access the Plugin manual.', 'woocommerce-epayco'),
            'card_settings_button_text'                 => __('Go to Settings', 'woocommerce-epayco'),
            'enabled_title'                             => __('Enable', 'woocommerce-epayco'),
            'enabled_subtitle'                          => __('By disabling it, you will disable all credit cards payments from ePayco.', 'woocommerce-epayco'),
            'enabled_descriptions_enabled'              => $enabledDescriptionsEnabled,
            'enabled_descriptions_disabled'             => $enabledDescriptionsDisabled,
            'title_title'                               => __('Title in the store Checkout', 'woocommerce-epayco'),
            'title_description'                         => __('Change the display text in Checkout, maximum characters: 85', 'woocommerce-epayco'),
            'title_default'                             => __('Credit and debit cards', 'woocommerce-epayco'),
            'title_desc_tip'                            => __('The text inserted here will not be translated to other languages', 'woocommerce-epayco'),
            'card_info_fees_title'                      => __('Installments Fees', 'woocommerce-epayco'),
            'card_info_fees_subtitle'                   => __('Set installment fees and whether they will be charged from the store or from the buyer.', 'woocommerce-epayco'),
            'card_info_fees_button_url'                 => __('Set fees', 'woocommerce-epayco'),
            'currency_conversion_title'                 => __('Convert Currency', 'woocommerce-epayco'),
            'currency_conversion_subtitle'              => __('Activate this option so that the value of the currency set in WooCommerce is compatible with the value of the currency you use in ePayco.', 'woocommerce-epayco'),
            'currency_conversion_descriptions_enabled'  => $currencyConversionDescriptionsEnabled,
            'currency_conversion_descriptions_disabled' => $currencyConversionDescriptionsDisabled,
            'wallet_button_title'                       => __('Payments via ePayco account', 'woocommerce-epayco'),
            'wallet_button_subtitle'                    => __('Your customers pay faster with saved cards, money balance or other available methods in their ePayco accounts.', 'woocommerce-epayco'),
            'wallet_button_descriptions_enabled'        => $walletButtonDescriptionsEnabled,
            'wallet_button_descriptions_disabled'       => $walletButtonDescriptionsDisabled,
            'wallet_button_preview_description'         => __('Check an example of how it will appear in your store:', 'woocommerce-epayco'),
            'advanced_configuration_title'              => __('Advanced configuration of the personalized payment experience', 'woocommerce-epayco'),
            'advanced_configuration_subtitle'           => __('Edit these advanced fields only when you want to modify the preset values.', 'woocommerce-epayco'),
            'binary_mode_title'                         => __('Automatic decline of payments without instant approval', 'woocommerce-epayco'),
            'binary_mode_subtitle'                      => __('Enable it if you want to automatically decline payments that are not instantly approved by banks or other institutions.', 'woocommerce-epayco'),
            'binary_mode_descriptions_enabled'          => $binaryModeDescriptionsEnabled,
            'binary_mode_descriptions_disabled'         => $binaryModeDescriptionsDisabled,
            'discount_title'                            => __('Discount in ePayco Checkouts', 'woocommerce-epayco'),
            'discount_description'                      => __('Choose a percentage value that you want to discount your customers for paying with Sdk.', 'woocommerce-epayco'),
            'discount_checkbox_label'                   => __('Activate and show this information on ePayco Checkout', 'woocommerce-epayco'),
            'commission_title'                          => __('Commission in ePayco Checkouts', 'woocommerce-epayco'),
            'commission_description'                    => __('Choose an additional percentage value that you want to charge as commission to your customers for paying with ePayco.', 'woocommerce-epayco'),
            'commission_checkbox_label'                 => __('Activate and show this information on ePayco Checkout', 'woocommerce-epayco'),
        ];
        $this->subscriptionGatewaySettings  = array_merge($this->subscriptionGatewaySettings, $this->setSupportLinkTranslations());
    }

    /**
     * Set ticket settings translations
     *
     * @return void
     */
    private function setTicketGatewaySettingsTranslations(): void
    {
        $currencyConversionDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $currencyConversionDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $this->ticketGatewaySettings = [
            'gateway_title'                => __('Invoice', 'woocommerce-epayco'),
            'gateway_description'          => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'method_title'                 => __('ePayco - Checkout API', 'woocommerce-epayco'),
            'header_title'                 => __('Transparent Checkout | Invoice or Loterica', 'woocommerce-epayco'),
            'header_description'           => __('With the Transparent Checkout, you can sell inside your store environment, without redirection and all the safety from ePayco.', 'woocommerce-epayco'),
            'card_settings_title'          => __('ePayco plugin general settings', 'woocommerce-epayco'),
            'card_settings_subtitle'       => __('Set the deadlines and fees, test your store or access the Plugin manual.', 'woocommerce-epayco'),
            'card_settings_button_text'    => __('Go to Settings', 'woocommerce-epayco'),
            'enabled_title'                => __('Enable the Checkout', 'woocommerce-epayco'),
            'enabled_subtitle'             => __('By disabling it, you will disable all invoice payments from ePayco Transparent Checkout.', 'woocommerce-epayco'),
            'enabled_enabled'              => __('The transparent checkout for tickets is <b>enabled</b>.', 'woocommerce-epayco'),
            'enabled_disabled'             => __('The transparent checkout for tickets is <b>disabled</b>.', 'woocommerce-epayco'),
            'title_title'                  => __('Title in the store Checkout', 'woocommerce-epayco'),
            'title_description'            => __('Change the display text in Checkout, maximum characters: 85', 'woocommerce-epayco'),
            'title_default'                => __('Invoice', 'woocommerce-epayco'),
            'title_desc_tip'               => __('The text inserted here will not be translated to other languages', 'woocommerce-epayco'),
            'currency_conversion_title'    => __('Convert Currency', 'woocommerce-epayco'),
            'currency_conversion_subtitle' => __('Activate this option so that the value of the currency set in WooCommerce is compatible with the value of the currency you use in Sdk.', 'woocommerce-epayco'),
            'currency_conversion_enabled'  => $currencyConversionDescriptionsEnabled,
            'currency_conversion_disabled' => $currencyConversionDescriptionsDisabled,
            'date_expiration_title'        => __('Payment Due', 'woocommerce-epayco'),
            'date_expiration_description'  => __('In how many days will cash payments expire.', 'woocommerce-epayco'),
            'advanced_title_title'         => __('Advanced configuration of the cash payment experience', 'woocommerce-epayco'),
            'advanced_description_title'   => __('Edit these advanced fields only when you want to modify the preset values.', 'woocommerce-epayco'),
            'stock_reduce_title'           => __('Reduce inventory', 'woocommerce-epayco'),
            'stock_reduce_subtitle'        => __('Activates inventory reduction during the creation of an order, whether or not the final payment is credited. Disable this option to reduce it only when payments are approved.', 'woocommerce-epayco'),
            'stock_reduce_enabled'         => __('Reduce inventory is <b>enabled</b>.', 'woocommerce-epayco'),
            'stock_reduce_disabled'        => __('Reduce inventory is <b>disabled</b>.', 'woocommerce-epayco'),
            'type_payments_title'          => __('Payment methods', 'woocommerce-epayco'),
            'type_payments_description'    => __('Enable the available payment methods', 'woocommerce-epayco'),
            'type_payments_desctip'        => __('Choose the available payment methods in your store.', 'woocommerce-epayco'),
            'type_payments_label'          => __('All payment methods', 'woocommerce-epayco'),
            'discount_title'               => __('Discount in ePayco Checkouts', 'woocommerce-epayco'),
            'discount_description'         => __('Choose a percentage value that you want to discount your customers for paying with Sdk.', 'woocommerce-epayco'),
            'discount_checkbox_label'      => __('Activate and show this information on Sdk Checkout', 'woocommerce-epayco'),
            'commission_title'             => __('Commission in ePayco Checkouts', 'woocommerce-epayco'),
            'commission_description'       => __('Choose an additional percentage value that you want to charge as commission to your customers for paying with ePayco.', 'woocommerce-epayco'),
            'commission_checkbox_label'    => __('Activate and show this information on ePayco Checkout', 'woocommerce-epayco'),
        ];
        $this->ticketGatewaySettings  = array_merge($this->ticketGatewaySettings, $this->setSupportLinkTranslations());
    }

    /**
     * Set ticket settings translations
     *
     * @return void
     */
    private function setDaviplataGatewaySettingsTranslations(): void
    {
        $this->daviplatatewaySettings = [
            'gateway_title'                => __('Daviplata', 'woocommerce-epayco'),
            'gateway_description'          => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'method_title'                 => __('Daviplata', 'woocommerce-epayco'),
            'header_title'                 => __('Daviplata', 'woocommerce-epayco'),
            'header_description'           => __('you can sell inside your store environment, without redirection and all the safety from ePayco.', 'woocommerce-epayco'),
            'enabled_title'                => __('Enable Daviplata', 'woocommerce-epayco'),
            'enabled_subtitle'             => __('By deactivating it, you will disable Checkout payment from ePayco', 'woocommerce-epayco'),
            'enabled_enabled'              => __('Daviplata is <b>enabled</b>.', 'woocommerce-epayco'),
            'enabled_disabled'             => __('Daviplata is <b>disabled</b>.', 'woocommerce-epayco'),
            'title_title'                  => __('Title in the store Checkout', 'woocommerce-epayco'),
            'title_description'            => __('Change the display text in Checkout, maximum characters: 85', 'woocommerce-epayco'),
            'title_default'                => __('Daviplata', 'woocommerce-epayco'),
            'title_desc_tip'               => __('The text inserted here will not be translated to other languages', 'woocommerce-epayco'),
        ];
        $this->daviplatatewaySettings  = array_merge($this->daviplatatewaySettings, $this->setSupportLinkTranslations());
    }

    /**
     * Set PSE settings translations
     *
     * @return void
     */
    private function setPseGatewaySettingsTranslations(): void
    {
        $currencyConversionDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $currencyConversionDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('Currency conversion is', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );

        $this->pseGatewaySettings = [
            'gateway_title'                => __('PSE', 'woocommerce-epayco'),
            'gateway_description'          => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'method_title'                 => __('ePayco - Checkout API', 'woocommerce-epayco'),
            'header_title'                 => __('PSE', 'woocommerce-epayco'),
            'header_description'           => __('you can sell inside your store environment, without redirection and all the safety from ePayco.', 'woocommerce-epayco'),
            'card_settings_title'          => __('ePayco plugin general settings', 'woocommerce-epayco'),
            'card_settings_subtitle'       => __('Set the deadlines and fees, test your store or access the Plugin manual.', 'woocommerce-epayco'),
            'card_settings_button_text'    => __('Go to Settings', 'woocommerce-epayco'),
            'enabled_title'                => __('Enable PSE', 'woocommerce-epayco'),
            'enabled_subtitle'             => __('By deactivating it, you will disable PSE payments from ePayco', 'woocommerce-epayco'),
            'enabled_enabled'              => __('PSE is <b>enabled</b>.', 'woocommerce-epayco'),
            'enabled_disabled'             => __('PSE is <b>disabled</b>.', 'woocommerce-epayco'),
            'title_title'                  => __('Title in the store Checkout', 'woocommerce-epayco'),
            'title_description'            => __('Change the display text in Checkout, maximum characters: 85', 'woocommerce-epayco'),
            'title_default'                => __('PSE', 'woocommerce-epayco'),
            'title_desc_tip'               => __('The text inserted here will not be translated to other languages', 'woocommerce-epayco'),
            'currency_conversion_title'    => __('Convert Currency', 'woocommerce-epayco'),
            'currency_conversion_subtitle' => __('Activate this option so that the value of the currency set in WooCommerce is compatible with the value of the currency you use in ePayco.', 'woocommerce-epayco'),
            'currency_conversion_enabled'  => $currencyConversionDescriptionsEnabled,
            'currency_conversion_disabled' => $currencyConversionDescriptionsDisabled,
            'advanced_title_title'         => __('Advanced configuration of the PSE payment experience', 'woocommerce-epayco'),
            'advanced_description_title'   => __('Edit these advanced fields only when you want to modify the preset values.', 'woocommerce-epayco'),
            'stock_reduce_title'           => __('Reduce inventory', 'woocommerce-epayco'),
            'stock_reduce_subtitle'        => __('Activates inventory reduction during the creation of an order, whether or not the final payment is credited. Disable this option to reduce it only when payments are approved.', 'woocommerce-epayco'),
            'stock_reduce_enabled'         => __('Reduce inventory is <b>enabled</b>.', 'woocommerce-epayco'),
            'stock_reduce_disabled'        => __('Reduce inventory is <b>disabled</b>.', 'woocommerce-epayco'),
            'type_payments_title'          => __('Payment methods', 'woocommerce-epayco'),
            'type_payments_description'    => __('Enable the available payment methods', 'woocommerce-epayco'),
            'type_payments_desctip'        => __('Choose the available payment methods in your store.', 'woocommerce-epayco'),
            'type_payments_label'          => __('All payment methods', 'woocommerce-epayco'),
            'discount_title'               => __('Discount in ePayco Checkouts', 'woocommerce-epayco'),
            'discount_description'         => __('Choose a percentage value that you want to discount your customers for paying with Sdk.', 'woocommerce-epayco'),
            'discount_checkbox_label'      => __('Activate and show this information on ePayco Checkout', 'woocommerce-epayco'),
            'commission_title'             => __('Commission in ePayco Checkouts', 'woocommerce-epayco'),
            'commission_description'       => __('Choose an additional percentage value that you want to charge as commission to your customers for paying with ePayco.', 'woocommerce-epayco'),
            'commission_checkbox_label'    => __('Activate and show this information on ePayco Checkout', 'woocommerce-epayco'),
        ];
        $this->pseGatewaySettings  = array_merge($this->pseGatewaySettings, $this->setSupportLinkTranslations());
    }

    /**
     * Set PSE settings translations
     *
     * @return void
     */
    private function setCheckoutGatewaySettingsTranslations(): void
    {
        $ePaycoCheckoutDescriptionsEnabled = sprintf(
            '%s <b>%s</b>.',
            __('One Page Checkout is', 'woocommerce-epayco'),
            __('enabled', 'woocommerce-epayco')
        );

        $ePaycoCheckoutDescriptionsDisabled = sprintf(
            '%s <b>%s</b>.',
            __('One Page Checkout is', 'woocommerce-epayco'),
            __('disabled', 'woocommerce-epayco')
        );
        $this->checkoutGatewaySettings = [
            'gateway_title'                => __('Checkout', 'woocommerce-epayco'),
            'gateway_description'          => __('Payments without leaving your store with our customizable checkout', 'woocommerce-epayco'),
            'method_title'                 => __('ePayco', 'woocommerce-epayco'),
            'header_title'                 => __('Checkout', 'woocommerce-epayco'),
            'header_description'           => __('you can sell inside your store environment, without redirection and all the safety from ePayco.', 'woocommerce-epayco'),
            'enabled_title'                => __('Enable ePayco', 'woocommerce-epayco'),
            'enabled_subtitle'             => __('By deactivating it, you will disable Checkout payment from ePayco', 'woocommerce-epayco'),
            'enabled_enabled'              => __('Checkout is <b>enabled</b>.', 'woocommerce-epayco'),
            'enabled_disabled'             => __('Checkout is <b>disabled</b>.', 'woocommerce-epayco'),
            'title_title'                  => __('Title in the store Checkout', 'woocommerce-epayco'),
            'title_description'            => __('Change the display text in Checkout, maximum characters: 85', 'woocommerce-epayco'),
            'title_default'                => __('Checkout', 'woocommerce-epayco'),
            'title_desc_tip'               => __('The text inserted here will not be translated to other languages', 'woocommerce-epayco'),
            'epayco_type_checkout_title'                 => __('Checkout mode', 'woocommerce-epayco'),
            'epayco_type_checkout_subtitle'              => __('Activate this option so that the payment experience is within your store environment, without redirection.', 'woocommerce-epayco'),
            'epayco_type_checkout_descriptions_enabled'  => $ePaycoCheckoutDescriptionsEnabled,
            'epayco_type_checkout_descriptions_disabled' => $ePaycoCheckoutDescriptionsDisabled,
            ];
        $this->checkoutGatewaySettings  = array_merge($this->checkoutGatewaySettings, $this->setSupportLinkTranslations());
    }


    /**
     * Set test mode settings translations
     *
     * @return void
     */
    private function setTestModeSettingsTranslations(): void
    {

        $testSubtitleOne = sprintf(
            '1. %s <a class="mp-settings-blue-text" id="mp-testmode-testuser-link" target="_blank" href="%s">%s</a>, %s.',
            __('Create your', 'woocommerce-epayco'),
            $this->links['epayco_test_user'],
            __('test user', 'woocommerce-epayco'),
            __('(Optional. Can be used in Production Mode and Test Mode, to test payments)', 'woocommerce-epayco')
        );

        $testSubtitleTwo = sprintf(
            '2. <a class="mp-settings-blue-text" id="mp-testmode-cardtest-link" target="_blank" href="%s">%s</a>, %s.',
            $this->links['docs_test_cards'],
            __('Use our test cards', 'woocommerce-epayco'),
            __('never use real cards', 'woocommerce-epayco')
        );

        $testSubtitleThree = sprintf(
            '3. <a class="mp-settings-blue-text" id="mp-testmode-store-link" target="_blank" href="%s">%s</a> %s.',
            $this->links['store_visit'],
            __('Visit your store', 'woocommerce-epayco'),
            __('to test purchases', 'woocommerce-epayco')
        );

        $this->testModeSettings = [
            'title_test_mode'         => __('3. Test your store before you start to sell', 'woocommerce-epayco'),
            'title_mode'              => __('Choose how you want to operate your store:', 'woocommerce-epayco'),
            'title_test'              => __('Test Mode', 'woocommerce-epayco'),
            'title_prod'              => __('Production Mode', 'woocommerce-epayco'),
            'title_message_prod'      => __('ePayco payment methods in Production Mode', 'woocommerce-epayco'),
            'title_message_test'      => __('ePayco payment methods in Test Mode', 'woocommerce-epayco'),
            'subtitle_test_mode'      => __('Select “Test Mode” if you want to try the payment experience before you start to sell or “Sales Mode” (Production) to start now.', 'woocommerce-epayco'),
            'subtitle_test'           => __('ePayco Checkouts Test.', 'woocommerce-epayco'),
            'subtitle_test_link'      => __('Test Mode rules.', 'woocommerce-epayco'),
            'subtitle_prod'           => __('ePayco Checkouts Production.', 'woocommerce-epayco'),
            'subtitle_message_prod'   => __('The clients can make real purchases in your store.', 'woocommerce-epayco'),
            'subtitle_test_one'       => $testSubtitleOne,
            'subtitle_test_two'       => $testSubtitleTwo,
            'subtitle_test_three'     => $testSubtitleThree,
            'badge_mode'              => __('Production', 'woocommerce-epayco'),
            'badge_test'              => __('test', 'woocommerce-epayco'),
            'button_test_mode'        => __('Save changes', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set configuration tips translations
     *
     * @return void
     */
    private function setConfigurationTipsTranslations(): void
    {
        $this->configurationTips = [
            'valid_store_tips'         => __('Store business fields are valid', 'woocommerce-epayco'),
            'invalid_store_tips'       => __('Store business fields could not be validated', 'woocommerce-epayco'),
            'valid_payment_tips'       => __('At least one payment method is enabled', 'woocommerce-epayco'),
            'invalid_payment_tips'     => __('No payment method enabled', 'woocommerce-epayco'),
            'valid_credentials_tips'   => __('Credentials fields are valid', 'woocommerce-epayco'),
            'invalid_credentials_tips' => __('Credentials fields could not be validated', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set validate credentials translations
     *
     * @return void
     */
    private function setValidateCredentialsTranslations(): void
    {
        $this->validateCredentials = [
            'valid_access_token'   => __('Valid Access Token', 'woocommerce-epayco'),
            'invalid_access_token' => __('Invalid Access Token', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set update credentials translations
     *
     * @return void
     */
    private function setUpdateCredentialsTranslations(): void
    {
        $this->updateCredentials = [
            'credentials_updated'              => __('Credentials were updated', 'woocommerce-epayco'),
            'no_test_mode_title'               => __('Your store has exited Test Mode and is making real sales in Production Mode.', 'woocommerce-epayco'),
            'no_test_mode_subtitle'            => __('To test the store, re-enter both test credentials.', 'woocommerce-epayco'),
            'invalid_credentials_title'        => __('Invalid credentials', 'woocommerce-epayco'),
            'invalid_credentials_subtitle'     => __('See our manual to learn', 'woocommerce-epayco'),
            'invalid_credentials_link_message' => __('how to enter the credentials the right way.', 'woocommerce-epayco'),
            'for_test_mode'                    => __(' for test mode', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set update store translations
     *
     * @return void
     */
    private function setUpdateStoreTranslations(): void
    {
        $this->updateStore = [
            'valid_configuration' => __('Store information is valid', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set currency translations
     *
     * @return void
     */
    private function setCurrencyTranslations(): void
    {
        $notCompatibleCurrencyConversion = sprintf(
            '<b>%s</b> %s',
            __('Attention:', 'woocommerce-epayco'),
            __('The currency settings you have in WooCommerce are not compatible with the currency you use in your Sdk account. Please activate the currency conversion.', 'woocommerce-epayco')
        );

        $baseConversionMessage = __('Make payments faster and safer', 'woocommerce-epayco');
        $this->currency = [
            'not_compatible_currency_conversion' => $notCompatibleCurrencyConversion,
            'now_we_convert'     => $this->generateConversionMessage($baseConversionMessage),
        ];
    }

    /**
     * Generate conversion message
     *
     * @param string $baseMessage
     *
     * @return string
     */
    private function generateConversionMessage(string $baseMessage): string
    {
        return sprintf('%s %s %s ', $baseMessage, get_woocommerce_currency(), __("to ", 'woocommerce-epayco'));
    }

    /**
     * Set status sync metabox translations
     *
     * @return void
     */
    private function setStatusSyncTranslations(): void
    {
        $this->statusSync = [
            'metabox_title'                                    => __('Payment status on ePayco', 'woocommerce-epayco'),
            'card_title'                                       => __('This is the payment status of your Sdk Activities. To check the order status, please refer to Order details.', 'woocommerce-epayco'),
            'link_description_success'                         => __('View purchase details at Sdk', 'woocommerce-epayco'),
            'sync_button_success'                              => __('Sync order status', 'woocommerce-epayco'),
            'link_description_pending'                         => __('View purchase details at Sdk', 'woocommerce-epayco'),
            'sync_button_pending'                              => __('Sync order status', 'woocommerce-epayco'),
            'link_description_failure'                         => __('Consult the reasons for refusal', 'woocommerce-epayco'),
            'sync_button_failure'                              => __('Sync order status', 'woocommerce-epayco'),
            'response_success'                                 => __('Order update successfully. This page will be reloaded...', 'woocommerce-epayco'),
            'response_error'                                   => __('Unable to update order:', 'woocommerce-epayco'),
            'alert_title_accredited'                           => __('Payment made', 'woocommerce-epayco'),
            'description_accredited'                           => __('Payment made by the buyer and already credited in the account.', 'woocommerce-epayco'),
            'alert_title_settled'                              => __('Call resolved', 'woocommerce-epayco'),
            'description_settled'                              => __('Please contact Sdk for further details.', 'woocommerce-epayco'),
            'alert_title_reimbursed'                           => __('Payment refunded', 'woocommerce-epayco'),
            'description_reimbursed'                           => __('Your refund request has been made. Please contact Sdk for further details.', 'woocommerce-epayco'),
            'alert_title_refunded'                             => __('Payment returned', 'woocommerce-epayco'),
            'description_refunded'                             => __('The payment has been returned to the client.', 'woocommerce-epayco'),
            'alert_title_partially_refunded'                   => __('Payment returned', 'woocommerce-epayco'),
            'description_partially_refunded'                   => __('The payment has been partially returned to the client.', 'woocommerce-epayco'),
            'alert_title_by_collector'                         => __('Payment canceled', 'woocommerce-epayco'),
            'description_by_collector'                         => __('The payment has been successfully canceled.', 'woocommerce-epayco'),
            'alert_title_by_payer'                             => __('Purchase canceled', 'woocommerce-epayco'),
            'description_by_payer'                             => __('The payment has been canceled by the customer.', 'woocommerce-epayco'),
            'alert_title_pending'                              => __('Pending payment', 'woocommerce-epayco'),
            'description_pending'                              => __('Awaiting payment from the buyer.', 'woocommerce-epayco'),
            'alert_title_pending_waiting_payment'              => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_waiting_payment'              => __('Awaiting payment from the buyer.', 'woocommerce-epayco'),
            'alert_title_pending_waiting_for_remedy'           => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_waiting_for_remedy'           => __('Awaiting payment from the buyer.', 'woocommerce-epayco'),
            'alert_title_pending_waiting_transfer'             => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_waiting_transfer'             => __('Awaiting payment from the buyer.', 'woocommerce-epayco'),
            'alert_title_pending_review_manual'                => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_review_manual'                => __('We are veryfing the payment. We will notify you by email in up to 6 hours if everything is fine so that you can deliver the product or provide the service.', 'woocommerce-epayco'),
            'alert_title_waiting_bank_confirmation'            => __('Declined payment', 'woocommerce-epayco'),
            'description_waiting_bank_confirmation'            => __('The card-issuing bank declined the payment. Please ask your client to use another card or to get in touch with the bank.', 'woocommerce-epayco'),
            'alert_title_pending_capture'                      => __('Payment authorized. Awaiting capture.', 'woocommerce-epayco'),
            'description_pending_capture'                      => __("The payment has been authorized on the client's card. Please capture the payment.", 'woocommerce-epayco'),
            'alert_title_in_process'                           => __('Payment in process', 'woocommerce-epayco'),
            'description_in_process'                           => __('Please wait or contact Sdk for further details', 'woocommerce-epayco'),
            'alert_title_pending_contingency'                  => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_contingency'                  => __('The bank is reviewing the payment. As soon as we have their confirmation, we will notify you via email so that you can deliver the product or provide the service.', 'woocommerce-epayco'),
            'alert_title_pending_card_validation'              => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_card_validation'              => __('Awaiting payment information validation.', 'woocommerce-epayco'),
            'alert_title_pending_online_validation'            => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_online_validation'            => __('Awaiting payment information validation.', 'woocommerce-epayco'),
            'alert_title_pending_additional_info'              => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_additional_info'              => __('Awaiting payment information validation.', 'woocommerce-epayco'),
            'alert_title_offline_process'                      => __('Pending payment', 'woocommerce-epayco'),
            'description_offline_process'                      => __('Please wait or contact Sdk for further details', 'woocommerce-epayco'),
            'alert_title_pending_challenge'                    => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_challenge'                    => __('Waiting for the buyer.', 'woocommerce-epayco'),
            'alert_title_pending_provider_response'            => __('Pending payment', 'woocommerce-epayco'),
            'description_pending_provider_response'            => __('Waiting for the card issuer.', 'woocommerce-epayco'),
            'alert_title_bank_rejected'                        => __('The card issuing bank declined the payment', 'woocommerce-epayco'),
            'description_bank_rejected'                        => __('Please recommend your customer to pay with another payment method or to contact their bank.', 'woocommerce-epayco'),
            'alert_title_rejected_by_bank'                     => __('The card issuing bank declined the payment', 'woocommerce-epayco'),
            'description_rejected_by_bank'                     => __('Please recommend your customer to pay with another payment method or to contact their bank.', 'woocommerce-epayco'),
            'alert_title_rejected_insufficient_data'           => __('Declined payment', 'woocommerce-epayco'),
            'description_rejected_insufficient_data'           => __('The card-issuing bank declined the payment. Please ask your client to use another card or to get in touch with the bank.', 'woocommerce-epayco'),
            'alert_title_bank_error'                           => __('The card issuing bank declined the payment', 'woocommerce-epayco'),
            'description_bank_error'                           => __('Please recommend your customer to pay with another payment method or to contact their bank.', 'woocommerce-epayco'),
            'alert_title_by_admin'                             => __('Sdk did not process the payment', 'woocommerce-epayco'),
            'description_by_admin'                             => __('Please contact Sdk for further details.', 'woocommerce-epayco'),
            'alert_title_expired'                              => __('Expired payment deadline', 'woocommerce-epayco'),
            'description_expired'                              => __('The client did not pay within the time limit.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_bad_filled_card_number'   => __('Your customer entered one or more incorrect card details', 'woocommerce-epayco'),
            'description_cc_rejected_bad_filled_card_number'   => __('Please ask them to enter to enter them again exactly as they appear on the card or on their bank app to complete the payment.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_bad_filled_security_code' => __('Your customer entered one or more incorrect card details', 'woocommerce-epayco'),
            'description_cc_rejected_bad_filled_security_code' => __('Please ask them to enter to enter them again exactly as they appear on the card or on their bank app to complete the payment.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_bad_filled_date'          => __('Your customer entered one or more incorrect card details', 'woocommerce-epayco'),
            'description_cc_rejected_bad_filled_date'          => __('Please ask them to enter to enter them again exactly as they appear on the card or on their bank app to complete the payment.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_high_risk'                => __('We protected you from a suspicious payment', 'woocommerce-epayco'),
            'description_cc_rejected_high_risk'                => __('For safety reasons, this transaction cannot be completed.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_fraud'                    => __('Declined payment', 'woocommerce-epayco'),
            'description_cc_rejected_fraud'                    => __('The buyer is suspended in our platform. Your client must contact us to check what happened.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_blacklist'                => __('For safety reasons, the card issuing bank declined the payment', 'woocommerce-epayco'),
            'description_cc_rejected_blacklist'                => __('Recommend your customer to pay with their usual payment method and device for online purchases.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_insufficient_amount'      => __("Your customer's credit card has no available limit", 'woocommerce-epayco'),
            'description_cc_rejected_insufficient_amount'      => __('Please ask them to pay with another card or to choose another payment method.', 'woocommerce-epayco'),
            'description_cc_rejected_insufficient_amount_cc'   => __('Please ask them to pay with another card or to choose another payment method.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_other_reason'             => __('The card issuing bank declined the payment', 'woocommerce-epayco'),
            'description_cc_rejected_other_reason'             => __('Please recommend your customer to pay with another payment method or to contact their bank.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_max_attempts'             => __('Your customer reached the limit of payment attempts with this card', 'woocommerce-epayco'),
            'description_cc_rejected_max_attempts'             => __('Please ask them to pay with another card or to choose another payment method.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_invalid_installments'     => __("Your customer's card  does not accept the number of installments selected", 'woocommerce-epayco'),
            'description_cc_rejected_invalid_installments'     => __('Please ask them to choose a different number of installments or to pay with another method.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_call_for_authorize'       => __('Your customer needs to authorize the payment through their bank', 'woocommerce-epayco'),
            'description_cc_rejected_call_for_authorize'       => __('Please ask them to call the telephone number on their card or to pay with another method.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_duplicated_payment'       => __('The payment was declined because your customer already paid for this purchase', 'woocommerce-epayco'),
            'description_cc_rejected_duplicated_payment'       => __('Check your approved payments to verify it.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_card_disabled'            => __("Your customer's card was is not activated yet", 'woocommerce-epayco'),
            'description_cc_rejected_card_disabled'            => __('Please ask them to contact their bank by calling the number on the back of their card or to pay with another method.', 'woocommerce-epayco'),
            'alert_title_payer_unavailable'                    => __('Declined payment', 'woocommerce-epayco'),
            'description_payer_unavailable'                    => __('The buyer is suspended in our platform. Your client must contact us to check what happened.', 'woocommerce-epayco'),
            'alert_title_rejected_high_risk'                   => __('We protected you from a suspicious payment', 'woocommerce-epayco'),
            'description_rejected_high_risk'                   => __('Recommend your customer to pay with their usual payment method and device for online purchases.', 'woocommerce-epayco'),
            'alert_title_rejected_by_regulations'              => __('Declined payment', 'woocommerce-epayco'),
            'description_rejected_by_regulations'              => __('This payment was declined because it did not pass Sdk security controls. Please ask your client to use another card.', 'woocommerce-epayco'),
            'alert_title_rejected_cap_exceeded'                => __('Declined payment', 'woocommerce-epayco'),
            'description_rejected_cap_exceeded'                => __('The amount exceeded the card limit. Please ask your client to use another card or to get in touch with the bank.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_3ds_challenge'            => __('Declined payment', 'woocommerce-epayco'),
            'description_cc_rejected_3ds_challenge'            => __('Please ask your client to use another card or to get in touch with the card issuer.', 'woocommerce-epayco'),
            'alert_title_rejected_other_reason'                => __('The card issuing bank declined the payment', 'woocommerce-epayco'),
            'description_rejected_other_reason'                => __('Please recommend your customer to pay with another payment method or to contact their bank.', 'woocommerce-epayco'),
            'alert_title_authorization_revoked'                => __('Declined payment', 'woocommerce-epayco'),
            'description_authorization_revoked'                => __('Please ask your client to use another card or to get in touch with the card issuer.', 'woocommerce-epayco'),
            'alert_title_cc_amount_rate_limit_exceeded'        => __('Pending payment', 'woocommerce-epayco'),
            'description_cc_amount_rate_limit_exceeded'        => __("The amount exceeded the card's limit. Please ask your client to use another card or to get in touch with the bank.", 'woocommerce-epayco'),
            'alert_title_cc_rejected_expired_operation'        => __('Expired payment deadline', 'woocommerce-epayco'),
            'description_cc_rejected_expired_operation'        => __('The client did not pay within the time limit.', 'woocommerce-epayco'),
            'alert_title_cc_rejected_bad_filled_other'         => __('Your customer entered one or more incorrect card details', 'woocommerce-epayco'),
            'description_cc_rejected_bad_filled_other'         => __('Please ask them to enter to enter them again exactly as they appear on the card or on their bank app to complete the payment.', 'woocommerce-epayco'),
            'description_cc_rejected_bad_filled_other_cc'      => __('Please ask them to enter to enter them again exactly as they appear on the card or on their bank app to complete the payment.', 'woocommerce-epayco'),
            'alert_title_rejected_call_for_authorize'          => __('Your customer needs to authorize the payment through their bank', 'woocommerce-epayco'),
            'description_rejected_call_for_authorize'          => __('Please ask them to call the telephone number on their card or to pay with another method.', 'woocommerce-epayco'),
            'alert_title_am_insufficient_amount'               => __("Your customer's debit card has insufficient funds", 'woocommerce-epayco'),
            'description_am_insufficient_amount'               => __('Please recommend your customer to pay with another card or to choose another payment method.', 'woocommerce-epayco'),
            'alert_title_generic'                              => __('Something went wrong and the payment was declined', 'woocommerce-epayco'),
            'description_generic'                              => __('Please recommend you customer to try again or to pay with another payment method.', 'woocommerce-epayco'),
        ];
    }


     /**
     * Set support link translations
     *
     * @return array with new translations
     */
    private function setSupportLinkTranslations(): array
    {
        return [
            'support_link_bold_text'        => __('Any questions?', 'woocommerce-epayco'),
            'support_link_text_before_link' => __('Please check the', 'woocommerce-epayco'),
            'support_link_text_with_link'   => __('FAQs', 'woocommerce-epayco'),
            'support_link_text_after_link'  => __('on the dev website.', 'woocommerce-epayco'),
        ];
    }

}
