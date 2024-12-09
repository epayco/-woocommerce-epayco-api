<?php

namespace Epayco\Woocommerce\Translations;

use Epayco\Woocommerce\Helpers\Links;

if (!defined('ABSPATH')) {
    exit;
}

class StoreTranslations
{
    /**
     * @var array
     */
    public $commonCheckout = [];

    /**
     * @var array
     */
    public $basicCheckout = [];

    /**
     * @var array
     */
    public $creditcardCheckout = [];

    /**
     * @var array
     */
    public $subscriptionCheckout = [];

    /**
     * @var array
     */
    public $epaycoCheckout = [];

    /**
     * @var array
     */
    public $ticketCheckout = [];

    /**
     * @var array
     */
    public $daviplataCheckout = [];

    /**
     * @var array
     */
    public $pseCheckout = [];

    /**
     * @var array
     */
    public $orderStatus = [];

    /**
     * @var array
     */
    public $commonMessages = [];

    /**
     * @var array
     */
    public $buyerRefusedMessages = [];

    /**
     * @var array
     */
    public $threeDsTranslations;

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

        $this->setCommonCheckoutTranslations();
        $this->setBasicCheckoutTranslations();
        $this->setCreditCardCheckoutTranslations ();
        $this->setSubscriptionCheckoutTranslations();
        $this->setTicketCheckoutTranslations();
        $this->setPseCheckoutTranslations();
        $this->setEpaycoCheckoutTranslations();
        $this->setDaviplataCheckoutTranslations();
        $this->setOrderStatusTranslations();
        $this->setCommonMessagesTranslations();
        $this->setbuyerRefusedMessagesTranslations();
        $this->set3dsTranslations();
    }

    /**
     * Set common checkout translations
     *
     * @return void
     */
    private function setCommonCheckoutTranslations(): void
    {
        $this->commonCheckout = [
            'discount_title'     => __('discount of', 'woocommerce-epayco'),
            'fee_title'          => __('fee of', 'woocommerce-epayco'),
            'text_concatenation' => __('and', 'woocommerce-epayco'),
            'shipping_title'     => __('Shipping service used by the store.', 'woocommerce-epayco'),
            'store_discount'     => __('Discount provided by store', 'woocommerce-epayco'),
            'cart_discount'      => __('Mercado Pago Discount', 'woocommerce-epayco'),
            'cart_commission'    => __('Mercado Pago Commission', 'woocommerce-epayco'),
            'message_error_amount'    => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),

        ];
    }

    /**
     * Set basic checkout translations
     *
     * @return void
     */
    private function setBasicCheckoutTranslations(): void
    {
        $this->basicCheckout = [
            'test_mode_title'                                 => __('Checkout Pro in Test Mode', 'woocommerce-epayco'),
            'test_mode_description'                           => __('Use ePayco\'s payment methods without real charges. ', 'woocommerce-epayco'),
            'test_mode_link_text'                             => __('See the rules for the test mode.', 'woocommerce-epayco'),
            'checkout_benefits_title'                         => __('Log in to Mercado Pago and earn benefits', 'woocommerce-epayco'),
            'checkout_benefits_title_phone'                   => __('Easy login', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_phone'                => __('Log in with the same email and password you use in Mercado Libre.', 'woocommerce-epayco'),
            'checkout_benefits_alt_phone'                     => __('Blue phone image', 'woocommerce-epayco'),
            'checkout_benefits_title_wallet'                  => __('Quick payments', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_wallet'               => __('Use your saved cards, Pix or available balance.', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_wallet_2'             => __('Use your available Mercado Pago Wallet balance or saved cards.', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_wallet_3'             => __('Use your available money or saved cards.', 'woocommerce-epayco'),
            'checkout_benefits_alt_wallet'                    => __('Blue wallet image', 'woocommerce-epayco'),
            'checkout_benefits_title_protection'              => __('Protected purchases', 'woocommerce-epayco'),
            'checkout_benefits_title_protection_2'            => __('Reliable purchases', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_protection'           => __('Get your money back in case you don\'t receive your product.', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_protection_2'         => __('Get help if you have a problem with your purchase.', 'woocommerce-epayco'),
            'checkout_benefits_alt_protection'                => __('Blue protection image', 'woocommerce-epayco'),
            'checkout_benefits_title_phone_installments'      => __('Installments option', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_phone_installments'   => __('Pay with or without a credit card.', 'woocommerce-epayco'),
            'checkout_benefits_subtitle_phone_installments_2' => __('Interest-free installments with selected banks.', 'woocommerce-epayco'),
            'checkout_benefits_alt_phone_installments'        => __('Blue phone installments image', 'woocommerce-epayco'),
            'payment_methods_title'                           => __('Available payment methods', 'woocommerce-epayco'),
            'checkout_redirect_text'                          => __('By continuing, you will be taken to Mercado Pago to safely complete your purchase.', 'woocommerce-epayco'),
            'checkout_redirect_alt'                           => __('Checkout Pro redirect info image', 'woocommerce-epayco'),
            'terms_and_conditions_description'                => __('By continuing, you agree with our', 'woocommerce-epayco'),
            'terms_and_conditions_link_text'                  => __('Terms and conditions', 'woocommerce-epayco'),
            'pay_with_mp_title'                               => __('Pay with Mercado Pago', 'woocommerce-epayco'),
            'cancel_url_text'                                 => __('Cancel &amp; Clear Cart', 'woocommerce-epayco'),
            'message_error_amount'                            => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),
            'success_message'                                 => __('Approved transaction', 'woocommerce-epayco'),
            'pending_message'                                 => __('Pending transaction', 'woocommerce-epayco'),
            'fail_message'                                    => __('Transaction rejected', 'woocommerce-epayco'),
            'payment_method'                                      => __('payment method', 'woocommerce-epayco'),
            'dateandtime'                                         => __('Date and time', 'woocommerce-epayco'),
            'response'                         => __('Response', 'woocommerce-epayco'),
            'totalValue'                       => __('Total value', 'woocommerce-epayco'),
            'description'                       => __('Description', 'woocommerce-epayco'),
            'reference'                       => __('reference', 'woocommerce-epayco'),
            'purchase'                        => __('Purchase details', 'woocommerce-epayco'),
            'iPaddress'                       => __('IP address', 'woocommerce-epayco'),
            'receipt'                         => __('Receipt', 'woocommerce-epayco'),
            'authorization'                   => __('Authorization', 'woocommerce-epayco'),
            'paymentMethod'                   => __('Payment method', 'woocommerce-epayco'),
            'epayco_refecence'                => __('ePayco Reference', 'woocommerce-epayco'),
        ];
    }


    /**
     * Set credits checkout translations
     *
     * @return void
     */
    private function setCreditCardCheckoutTranslations (): void
    {
        $this->creditcardCheckout = [
            'message_error_amount'                                => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),
            'test_mode_title'                                     => __('Test Mode', 'woocommerce-epayco'),
            'test_mode_description'                               => __('Use ePayco\'s payment methods without real charges. ', 'woocommerce-epayco'),
            'test_mode_link_text'                                 => __('See the rules for the test mode.', 'woocommerce-epayco'),
            'card_detail'                                         => __('Card details', 'woocommerce-epayco'),
            'card_form_title'                                     => __('Card details', 'woocommerce-epayco'),
            'card_holder_name_input_label'                        => __('Holder name as it appears on the card', 'woocommerce-epayco'),
            'card_holder_name_input_helper'                       => __('Holder name is required', 'woocommerce-epayco'),
            'card_number_input_label'                             => __('Card number', 'woocommerce-epayco'),
            'card_number_input_helper'                            => __('Required Card number', 'woocommerce-epayco'),
            'card_expiration_input_label'                         => __('Expiration', 'woocommerce-epayco'),
            'card_expiration_input_helper'                        => __('Required data', 'woocommerce-epayco'),
            'customer_data'                                       => __('Customer data', 'woocommerce-epayco'),
            'input_helper_message_expiration_date_invalid_type'   => __('Expiration date invalid', 'woocommerce-epayco'),
            'input_helper_message_expiration_date_invalid_length' => __('Expiration date incomplete', 'woocommerce-epayco'),
            'input_helper_message_expiration_date_invalid_value'  => __('Expiration date invalid', 'woocommerce-epayco'),
            'card_security_code_input_label'                      => __('Security Code', 'woocommerce-epayco'),
            'card_security_code_input_helper'                     => __('Required data', 'woocommerce-epayco'),
            'input_helper_message_security_code_invalid_type'     => __('Security code is required', 'woocommerce-epayco'),
            'input_helper_message_security_code_invalid_length'   => __('Security code incomplete', 'woocommerce-epayco'),
            'card_fees_input_label'                               => __('Fees', 'woocommerce-epayco'),
            'card_customer_title'                                 => __('Customer data', 'woocommerce-epayco'),
            'card_document_input_label'                           => __('Holder document', 'woocommerce-epayco'),
            'card_document_input_helper'                          => __('Invalid document', 'woocommerce-epayco'),
            'card_holder_address_input_label'                     => __('Address ', 'woocommerce-epayco'),
            'card_holder_address_input_helper'                    => __('Holder address is required', 'woocommerce-epayco'),
            'card_holder_email_input_label'                       => __('Email', 'woocommerce-epayco'),
            'card_holder_email_input_helper'                      => __('Holder email is required', 'woocommerce-epayco'),
            'input_helper_message_card_holder_email'              => __('Holder email invalid', 'woocommerce-epayco'),
            'input_ind_phone_label'                               => __('Holder Phone', 'woocommerce-epayco'),
            'input_ind_phone_helper'                               => __('Invalid Phone', 'woocommerce-epayco'),
            'input_country_label'                                 => __('Holder Country', 'woocommerce-epayco'),
            'input_country_helper'                                => __('Invalid City', 'woocommerce-epayco'),
            'terms_and_conditions_label'                          => __('I confirm and accept the', 'woocommerce-epayco'),
            'terms_and_conditions_description'                    => __('of ePayco', 'woocommerce-epayco'),
            'terms_and_conditions_link_text'                      => __('Terms and conditions', 'woocommerce-epayco'),
            'success_message'                  => __('Approved transaction', 'woocommerce-epayco'),
            'pending_message'                  => __('Pending transaction', 'woocommerce-epayco'),
            'fail_message'                  => __('Transaction rejected', 'woocommerce-epayco'),
            'error_message'                    => __('Payment has failed', 'woocommerce-epayco'),
            'error_description'                => __('Please try again later.', 'woocommerce-epayco'),
            'payment_method'                   => __('payment method', 'woocommerce-epayco'),
            'dateandtime'                      => __('Date and time', 'woocommerce-epayco'),
            'response'                         => __('Response', 'woocommerce-epayco'),
            'totalValue'                       => __('Total value', 'woocommerce-epayco'),
            'description'                       => __('Description', 'woocommerce-epayco'),
            'reference'                       => __('reference', 'woocommerce-epayco'),
            'purchase'                        => __('Purchase details', 'woocommerce-epayco'),
            'iPaddress'                       => __('IP address', 'woocommerce-epayco'),
            'receipt'                         => __('Receipt', 'woocommerce-epayco'),
            'authorization'                   => __('Authorization', 'woocommerce-epayco'),
            'paymentMethod'                   => __('Payment method', 'woocommerce-epayco'),
            'epayco_refecence'                => __('ePayco Reference', 'woocommerce-epayco'),
        ];
    }


    /**
     * Set credits checkout translations
     *
     * @return void
     */
    private function setSubscriptionCheckoutTranslations(): void
    {
        $this->subscriptionCheckout = [
            'message_error_amount'                                => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),
            'test_mode_title'                                     => __('Test Mode', 'woocommerce-epayco'),
            'test_mode_description'                               => __('Use ePayco\'s payment methods without real charges. ', 'woocommerce-epayco'),
            'test_mode_link_text'                                 => __('See the rules for the test mode.', 'woocommerce-epayco'),
            'card_detail'                                         => __('Card details', 'woocommerce-epayco'),
            'card_form_title'                                     => __('Subscription', 'woocommerce-epayco'),
            'card_holder_name_input_label'                        => __('Holder name as it appears on the card', 'woocommerce-epayco'),
            'card_holder_name_input_helper'                       => __('Holder name is required', 'woocommerce-epayco'),
            'card_number_input_label'                             => __('Card number', 'woocommerce-epayco'),
            'card_number_input_helper'                            => __('Required Card number', 'woocommerce-epayco'),
            'card_expiration_input_label'                         => __('Expiration', 'woocommerce-epayco'),
            'card_expiration_input_helper'                        => __('Required data', 'woocommerce-epayco'),
            'customer_data'                                       => __('Customer data', 'woocommerce-epayco'),
            'input_helper_message_expiration_date_invalid_type'   => __('Expiration date invalid', 'woocommerce-epayco'),
            'input_helper_message_expiration_date_invalid_length' => __('Expiration date incomplete', 'woocommerce-epayco'),
            'input_helper_message_expiration_date_invalid_value'  => __('Expiration date invalid', 'woocommerce-epayco'),
            'card_security_code_input_label'                      => __('Security Code', 'woocommerce-epayco'),
            'card_security_code_input_helper'                     => __('Required data', 'woocommerce-epayco'),
            'input_helper_message_security_code_invalid_type'     => __('Security code is required', 'woocommerce-epayco'),
            'input_helper_message_security_code_invalid_length'   => __('Security code incomplete', 'woocommerce-epayco'),
            'card_customer_title'                                 => __('Customer data', 'woocommerce-epayco'),
            'card_document_input_label'                           => __('Holder document', 'woocommerce-epayco'),
            'card_document_input_helper'                          => __('Invalid document', 'woocommerce-epayco'),
            'card_holder_address_input_label'                     => __('Address ', 'woocommerce-epayco'),
            'card_holder_address_input_helper'                    => __('Holder address is required', 'woocommerce-epayco'),
            'card_holder_email_input_label'                       => __('Email', 'woocommerce-epayco'),
            'card_holder_email_input_helper'                      => __('Holder email is required', 'woocommerce-epayco'),
            'input_helper_message_card_holder_email'              => __('Holder email invalid', 'woocommerce-epayco'),
            'input_ind_phone_label'                               => __('Holder Phone', 'woocommerce-epayco'),
            'input_ind_phone_helper'                              => __('Invalid Phone', 'woocommerce-epayco'),
            'input_country_label'                                 => __('Holder Country', 'woocommerce-epayco'),
            'input_country_helper'                                => __('Invalid City', 'woocommerce-epayco'),
            'terms_and_conditions_label'                          => __('I confirm and accept the', 'woocommerce-epayco'),
            'terms_and_conditions_description'                    => __('of ePayco', 'woocommerce-epayco'),
            'terms_and_conditions_link_text'                      => __('Terms and conditions', 'woocommerce-epayco'),
            'success_message'                                     => __('Approved transaction', 'woocommerce-epayco'),
            'pending_message'                                     => __('Pending transaction', 'woocommerce-epayco'),
            'fail_message'                                        => __('Transaction rejected', 'woocommerce-epayco'),
            'error_message'                                       => __('Payment has failed', 'woocommerce-epayco'),
            'error_description'                                   => __('Please try again later.', 'woocommerce-epayco'),
            'payment_method'                                      => __('payment method', 'woocommerce-epayco'),
            'dateandtime'                                         => __('Date and time', 'woocommerce-epayco'),
            'response'                         => __('Response', 'woocommerce-epayco'),
            'totalValue'                       => __('Total value', 'woocommerce-epayco'),
            'description'                       => __('Description', 'woocommerce-epayco'),
            'reference'                       => __('reference', 'woocommerce-epayco'),
            'purchase'                        => __('Purchase details', 'woocommerce-epayco'),
            'iPaddress'                       => __('IP address', 'woocommerce-epayco'),
            'receipt'                         => __('Receipt', 'woocommerce-epayco'),
            'authorization'                   => __('Authorization', 'woocommerce-epayco'),
            'paymentMethod'                   => __('Payment method', 'woocommerce-epayco'),
            'epayco_refecence'                => __('ePayco Reference', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set pix checkout translations
     *
     * @return void
     */
    private function setOrderStatusTranslations(): void
    {
        $this->orderStatus = [
            'payment_approved' => __('Payment approved.', 'woocommerce-epayco'),
            'pending_pix'      => __('Waiting for the Pix payment.', 'woocommerce-epayco'),
            'pending_ticket'   => __('Waiting for the ticket payment.', 'woocommerce-epayco'),
            'pending'          => __('The customer has not made the payment yet.', 'woocommerce-epayco'),
            'in_process'       => __('Payment is pending review.', 'woocommerce-epayco'),
            'rejected'         => __('Payment was declined. The customer can try again.', 'woocommerce-epayco'),
            'refunded'         => __('Payment was returned to the customer.', 'woocommerce-epayco'),
            'partial_refunded' => __('The payment was partially returned to the customer. the amount refunded was : ', 'woocommerce-epayco'),
            'cancelled'        => __('Payment was canceled.', 'woocommerce-epayco'),
            'in_mediation'     => __('The payment is in mediation or the purchase was unknown by the customer.', 'woocommerce-epayco'),
            'charged_back'     => __('The payment is in mediation or the purchase was unknown by the customer.', 'woocommerce-epayco'),
            'validate_order_1' => __('The payment', 'woocommerce-epayco'),
            'validate_order_2' => __('was notified by Mercado Pago with status', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set checkout ticket translations
     *
     * @return void
     */
    private function setTicketCheckoutTranslations(): void
    {
        $this->ticketCheckout = [
            'message_error_amount'             => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),
            'test_mode_title'                  => __('Offline Methods in Test Mode', 'woocommerce-epayco'),
            'test_mode_description'            => __('You can test the flow to generate an invoice, but you cannot finalize the payment.', 'woocommerce-epayco'),
            'test_mode_link_text'              => __('See the rules for the test mode.', 'woocommerce-epayco'),
            'input_name_label'                 => __('Holder name', 'woocommerce-epayco'),
            'input_name_helper'                => __('Invalid name', 'woocommerce-epayco'),
            'input_email_label'                => __('Holder email', 'woocommerce-epayco'),
            'input_email_helper'               => __('Invalid email', 'woocommerce-epayco'),
            'input_address_label'              => __('Holder address', 'woocommerce-epayco'),
            'input_address_helper'             => __('Invalid adress', 'woocommerce-epayco'),
            'input_ind_phone_label'            => __('Holder cellphone', 'woocommerce-epayco'),
            'input_ind_phone_helper'           => __('Invalid cellphone', 'woocommerce-epayco'),
            'person_type_label'                => __('Person type', 'woocommerce-epayco'),
            'input_document_label'             => __('Holder document', 'woocommerce-epayco'),
            'input_document_helper'            => __('Invalid document', 'woocommerce-epayco'),
            'input_country_label'              => __('Holder Country', 'woocommerce-epayco'),
            'input_country_helper'             => __('Invalid City', 'woocommerce-epayco'),
            'ticket_text_label'                => __('Select where you want to pay', 'woocommerce-epayco'),
            'input_table_button'               => __('more options', 'woocommerce-epayco'),
            'input_helper_label'               => __('Select a payment method', 'woocommerce-epayco'),
            'terms_and_conditions_label'       => __('I confirm and accept the', 'woocommerce-epayco'),
            'terms_and_conditions_description' => __('of ePayco', 'woocommerce-epayco'),
            'terms_and_conditions_link_text'   => __('Terms and conditions', 'woocommerce-epayco'),
        ];
    }


    /**
     * Set checkout pse translations
     *
     * @return void
     */
    private function setPseCheckoutTranslations(): void
    {
        $this->pseCheckout = [
            'message_error_amount'             => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),
            'test_mode_title'                  => __('Test Mode', 'woocommerce-epayco'),
            'test_mode_description'            => __('You can test the flow to generate a payment with PSE', 'woocommerce-epayco'),
            'test_mode_link_text'              => __('See the rules for the test mode.', 'woocommerce-epayco'),
            'input_name_label'                 => __('Holder name', 'woocommerce-epayco'),
            'input_name_helper'                => __('Invalid name', 'woocommerce-epayco'),
            'input_email_label'                => __('Email', 'woocommerce-epayco'),
            'input_email_helper'               => __('Invalid email', 'woocommerce-epayco'),
            'input_address_label'              => __('Address', 'woocommerce-epayco'),
            'input_address_helper'             => __('Invalid address', 'woocommerce-epayco'),
            'input_document_label'             => __('Holder document', 'woocommerce-epayco'),
            'input_document_helper'            => __('Invalid document', 'woocommerce-epayco'),
            'input_ind_phone_label'            => __('Cellphone', 'woocommerce-epayco'),
            'input_ind_phone_helper'           => __('Invalid cellphone', 'woocommerce-epayco'),
            'input_country_label'              => __('Holder Country', 'woocommerce-epayco'),
            'input_country_helper'             => __('Invalid City', 'woocommerce-epayco'),
            'person_type_label'                => __('Person type ', 'woocommerce-epayco'),
            'financial_institutions_label'     => __('Bank', 'woocommerce-epayco'),
            'financial_institutions_helper'    => __('Select the Bank', 'woocommerce-epayco'),
            'financial_placeholder'            => __('Select the institution', 'woocommerce-epayco'),
            'terms_and_conditions_label'                          => __('I confirm and accept the', 'woocommerce-epayco'),
            'terms_and_conditions_description'                    => __('of ePayco', 'woocommerce-epayco'),
            'terms_and_conditions_link_text'                      => __('Terms and conditions', 'woocommerce-epayco'),
            'success_message'                  => __('Approved transaction', 'woocommerce-epayco'),
            'pending_message'                  => __('Pending transaction', 'woocommerce-epayco'),
            'fail_message'                     => __('Transaction rejected', 'woocommerce-epayco'),
            'error_message'                    => __('Payment has failed', 'woocommerce-epayco'),
            'error_description'                => __('Please try again later.', 'woocommerce-epayco'),
            'payment_method'                   => __('payment method', 'woocommerce-epayco'),
            'dateandtime'                      => __('Date and time', 'woocommerce-epayco'),
            'response'                         => __('Response', 'woocommerce-epayco'),
            'totalValue'                       => __('Total value', 'woocommerce-epayco'),
            'description'                       => __('Description', 'woocommerce-epayco'),
            'reference'                       => __('reference', 'woocommerce-epayco'),
            'purchase'                        => __('Purchase details', 'woocommerce-epayco'),
            'iPaddress'                       => __('IP address', 'woocommerce-epayco'),
            'receipt'                         => __('Receipt', 'woocommerce-epayco'),
            'authorization'                   => __('Authorization', 'woocommerce-epayco'),
            'paymentMethod'                   => __('Payment method', 'woocommerce-epayco'),
            'epayco_refecence'                => __('ePayco Reference', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set common messages translations
     *
     * @return void
     */
    private function setEpaycoCheckoutTranslations(): void
    {
        $this->epaycoCheckout = [
            'test_mode_title'                  => __('Test Mode', 'woocommerce-epayco'),
            'test_mode_description'            => __('You can test the flow to generate a payment with ePayco', 'woocommerce-epayco'),
            'test_mode_link_text'              => __('See the rules for the test mode.', 'woocommerce-epayco'),
            'print_ticket_label'               => __('Great, we processed your purchase order. Complete the payment with ticket so that we finish approving it.', 'woocommerce-epayco'),
            'message_error_amount'             => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),
            'terms_and_conditions_label'                          => __('I confirm and accept the', 'woocommerce-epayco'),
            'terms_and_conditions_description'                    => __('of ePayco', 'woocommerce-epayco'),
            'terms_and_conditions_link_text'                      => __('Terms and conditions', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set common messages translations
     *
     * @return void
     */
    private function setDaviplataCheckoutTranslations(): void
    {
        $this->daviplataCheckout = [
            'message_error_amount'             => __('There was an error. Please try again in a few minutes.', 'woocommerce-epayco'),
            'test_mode_title'                  => __('Test Mode', 'woocommerce-epayco'),
            'test_mode_description'            => __('You can test the flow to generate a payment with Daviplata', 'woocommerce-epayco'),
            'test_mode_link_text'              => __('See the rules for the test mode.', 'woocommerce-epayco'),
            'input_name_label'                 => __('Holder name', 'woocommerce-epayco'),
            'input_name_helper'                => __('Invalid name', 'woocommerce-epayco'),
            'input_email_label'                => __('Email', 'woocommerce-epayco'),
            'input_email_helper'               => __('Invalid email', 'woocommerce-epayco'),
            'input_address_label'              => __('Address', 'woocommerce-epayco'),
            'input_address_helper'             => __('Invalid address', 'woocommerce-epayco'),
            'input_ind_phone_label'            => __('Cellphone', 'woocommerce-epayco'),
            'input_ind_phone_helper'           => __('Invalid cellphone', 'woocommerce-epayco'),
            'person_type_label'                => __('Person type', 'woocommerce-epayco'),
            'input_document_label'             => __('Holder document', 'woocommerce-epayco'),
            'input_document_helper'            => __('Invalid document', 'woocommerce-epayco'),
            'input_country_label'              => __('Holder Country', 'woocommerce-epayco'),
            'input_country_helper'             => __('Invalid City', 'woocommerce-epayco'),
            'terms_and_conditions_label'                          => __('I confirm and accept the', 'woocommerce-epayco'),
            'terms_and_conditions_description'                    => __('of ePayco', 'woocommerce-epayco'),
            'terms_and_conditions_link_text'                      => __('Terms and conditions', 'woocommerce-epayco'),
            'success_message'                  => __('Approved transaction', 'woocommerce-epayco'),
            'pending_message'                  => __('Pending transaction', 'woocommerce-epayco'),
            'fail_message'                  => __('Transaction rejected', 'woocommerce-epayco')
        ];
    }

    /**
     * Set common messages translations
     *
     * @return void
     */
    private function setCommonMessagesTranslations(): void
    {
        $this->commonMessages = [
            'cho_default_error'                        => __('A problem was occurred when processing your payment. Please, try again.', 'woocommerce-epayco'),
            'cho_form_error'                           => __('A problem was occurred when processing your payment. Are you sure you have correctly filled all information in the checkout form?', 'woocommerce-epayco'),
            'cho_see_order_form'                       => __('See your order form', 'woocommerce-epayco'),
            'cho_payment_declined'                     => __('Your payment was declined. You can try again.', 'woocommerce-epayco'),
            'cho_button_try_again'                     => __('Click to try again', 'woocommerce-epayco'),
            'cho_accredited'                           => __('That\'s it, payment accepted!', 'woocommerce-epayco'),
            'cho_pending_contingency'                  => __('We are processing your payment. In less than an hour we will send you the result by email.', 'woocommerce-epayco'),
            'cho_pending_review_manual'                => __('We are processing your payment. In less than 2 days we will send you by email if the payment has been approved or if additional information is needed.', 'woocommerce-epayco'),
            'cho_cc_rejected_bad_filled_card_number'   => __('Check the card number.', 'woocommerce-epayco'),
            'cho_cc_rejected_bad_filled_date'          => __('Check the expiration date.', 'woocommerce-epayco'),
            'cho_cc_rejected_bad_filled_other'         => __('Check the information provided.', 'woocommerce-epayco'),
            'cho_cc_rejected_bad_filled_security_code' => __('Check the informed security code.', 'woocommerce-epayco'),
            'cho_cc_rejected_card_error'               => __('Your payment cannot be processed.', 'woocommerce-epayco'),
            'cho_cc_rejected_blacklist'                => __('Your payment cannot be processed.', 'woocommerce-epayco'),
            'cho_cc_rejected_call_for_authorize'       => __('You must authorize payments for your orders.', 'woocommerce-epayco'),
            'cho_cc_rejected_card_disabled'            => __('Contact your card issuer to activate it. The phone is on the back of your card.', 'woocommerce-epayco'),
            'cho_cc_rejected_duplicated_payment'       => __('You have already made a payment of this amount. If you have to pay again, use another card or other method of payment.', 'woocommerce-epayco'),
            'cho_cc_rejected_high_risk'                => __('Your payment was declined. Please select another payment method. It is recommended in cash.', 'woocommerce-epayco'),
            'cho_cc_rejected_insufficient_amount'      => __('Your payment does not have sufficient funds.', 'woocommerce-epayco'),
            'cho_cc_rejected_invalid_installments'     => __('Payment cannot process the selected fee.', 'woocommerce-epayco'),
            'cho_cc_rejected_max_attempts'             => __('You have reached the limit of allowed attempts. Choose another card or other payment method.', 'woocommerce-epayco'),
            'invalid_users'                            => __('<strong>Invalid transaction attempt</strong><br>You are trying to perform a productive transaction using test credentials, or test transaction using productive credentials. Please ensure that you are using the correct environment settings for the desired action.', 'woocommerce-epayco'),
            'invalid_operators'                        => __('<strong>Invalid transaction attempt</strong><br>It is not possible to pay with the email address entered. Please enter another e-mail address.', 'woocommerce-epayco'),
            'cho_default'                              => __('This payment method cannot process your payment.', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set rejected payment messages translations for buyer
     *
     * @return void
     */
    private function setbuyerRefusedMessagesTranslations(): void
    {
        $this->buyerRefusedMessages = [
            'buyer_cc_rejected_call_for_authorize'          => __('<strong>Your bank needs you to authorize the payment</strong><br>Please call the telephone number on your card or pay with another method.', 'woocommerce-epayco'),
            'buyer_cc_rejected_high_risk'                   => __('<strong>For safety reasons, your payment was declined</strong><br>We recommended paying with your usual payment method and device for online purchases.', 'woocommerce-epayco'),
            'buyer_rejected_high_risk'                      => __('<strong>For safety reasons, your payment was declined</strong><br>We recommended paying with your usual payment method and device for online purchases.', 'woocommerce-epayco'),
            'buyer_cc_rejected_bad_filled_other'            => __('<strong>One or more card details were entered incorrecctly</strong><br>Please enter them again as they appear on the card to complete the payment.', 'woocommerce-epayco'),
            'buyer_cc_rejected_bad_filled_security_code'    => __('<strong>One or more card details were entered incorrecctly</strong><br>Please enter them again as they appear on the card to complete the payment.', 'woocommerce-epayco'),
            'buyer_cc_rejected_bad_filled_date'             => __('<strong>One or more card details were entered incorrecctly</strong><br>Please enter them again as they appear on the card to complete the payment.', 'woocommerce-epayco'),
            'buyer_cc_rejected_bad_filled_card_number'      => __('<strong>One or more card details were entered incorrecctly</strong><br>Please enter them again as they appear on the card to complete the payment.', 'woocommerce-epayco'),
            'buyer_cc_rejected_insufficient_amount'         => __('<strong>Your credit card has no available limit</strong><br>Please pay using another card or choose another payment method.', 'woocommerce-epayco'),
            'buyer_insufficient_amount'                     => __('<strong>Your debit card has insufficient founds</strong><br>Please pay using another card or choose another payment method.', 'woocommerce-epayco'),
            'buyer_cc_rejected_invalid_installments'        => __('<strong>Your card does not accept the number of installments selected</strong><br>Please choose a different number of installments or use a different payment method .', 'woocommerce-epayco'),
            'buyer_cc_rejected_card_disabled'               => __('<strong>You need to activate your card</strong><br>Please contact your bank by calling the number on the back of your card or choose another payment method.', 'woocommerce-epayco'),
            'buyer_cc_rejected_max_attempts'                => __('<strong>You reached the limit of payment attempts with this card</strong><br>Please pay using another card or choose another payment method.', 'woocommerce-epayco'),
            'buyer_cc_rejected_duplicated_payment'          => __('<strong>Your payment was declined because you already paid for this purchase</strong><br>Check your card transactions to verify it.', 'woocommerce-epayco'),
            'buyer_bank_error'                              => __('<strong>The card issuing bank declined the payment</strong><br>We recommended paying with another payment method or contact your bank.', 'woocommerce-epayco'),
            'buyer_cc_rejected_other_reason'                => __('<strong>The card issuing bank declined the payment</strong><br>We recommended paying with another payment method or contact your bank.', 'woocommerce-epayco'),
            'buyer_rejected_by_bank'                        => __('<strong>The card issuing bank declined the payment</strong><br>We recommended paying with another payment method or contact your bank.', 'woocommerce-epayco'),
            'buyer_cc_rejected_blacklist'                   => __('<strong>For safety reasons, the card issuing bank declined the payment</strong><br>We recommended paying with your usual payment method and device for online purchases.', 'woocommerce-epayco'),
            'buyer_default'                                 => __('<strong>Your payment was declined because something went wrong</strong><br>We recommended trying again or paying with another method.', 'woocommerce-epayco'),
        ];
    }

    /**
     * Set credits checkout translations
     *
     * @return void
     */
    private function set3dsTranslations(): void
    {
        $this->threeDsTranslations = [
            'title_loading_3ds_frame'    => __('We are taking you to validate the card', 'woocommerce-epayco'),
            'title_loading_3ds_frame2'   => __('with your bank', 'woocommerce-epayco'),
            'text_loading_3ds_frame'     => __('We need to confirm that you are the cardholder.', 'woocommerce-epayco'),
            'title_loading_3ds_response' => __('We are receiving the response from your bank', 'woocommerce-epayco'),
            'title_3ds_frame'            => __('Complete the bank validation so your payment can be approved', 'woocommerce-epayco'),
            'tooltip_3ds_frame'          => __('Please keep this page open. If you close it, you will not be able to resume the validation.', 'woocommerce-epayco'),
            'message_3ds_declined'       => __('<b>For safety reasons, your payment was declined</b><br>We recommend paying with your usual payment method and device for online purchases.', 'woocommerce-epayco'),
        ];
    }
}
