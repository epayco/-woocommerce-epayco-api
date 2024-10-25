<?php

namespace Epayco\Woocommerce\Gateways;

use Epayco\Woocommerce\Exceptions\InvalidCheckoutDataException;
use Epayco\Woocommerce\Helpers\Form;
use Epayco\Woocommerce\Helpers\Numbers;
use Epayco\Woocommerce\Transactions\CustomTransaction;
use Epayco\Woocommerce\Transactions\WalletButtonTransaction;
use Epayco\Woocommerce\Exceptions\ResponseStatusException;

if (!defined('ABSPATH')) {
    exit;
}

class CustomGateway extends AbstractGateway
{
    /**
     * @const
     */
    public const ID = 'woo-epayco-custom';

    /**
     * @const
     */
    public const CHECKOUT_NAME = 'checkout-custom';

    /**
     * @const
     */
    public const WEBHOOK_API_NAME = 'WC_Epayco_Custom_Gateway';

    /**
     * @const
     */
    public const LOG_SOURCE = 'Epayco_CustomGateway';

    /**
     * CustomGateway constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->adminTranslations = $this->epayco->adminTranslations->customGatewaySettings;
        $this->storeTranslations = $this->epayco->storeTranslations->customCheckout;

        $this->id        = self::ID;
        $this->icon      = $this->epayco->hooks->gateway->getGatewayIcon('icon-blue-card');
        $this->iconAdmin = $this->epayco->hooks->gateway->getGatewayIcon('icon-blue-card-admin');
        $this->title     = $this->epayco->storeConfig->getGatewayTitle($this, $this->adminTranslations['gateway_title']);

        $this->init_form_fields();
        $this->payment_scripts($this->id);

        $this->description        = $this->adminTranslations['gateway_description'];
        $this->method_title       = $this->adminTranslations['gateway_method_title'];
        $this->method_description = $this->adminTranslations['gateway_method_description'];
        $this->discount           = $this->getActionableValue('gateway_discount', 0);
        $this->commission         = $this->getActionableValue('commission', 0);

        $this->epayco->hooks->gateway->registerUpdateOptions($this);
        $this->epayco->hooks->gateway->registerGatewayTitle($this);
        $this->epayco->hooks->gateway->registerThankYouPage($this->id, [$this, 'renderInstallmentsRateDetails']);

        $this->epayco->hooks->order->registerOrderDetailsAfterOrderTable([$this, 'renderInstallmentsRateDetails']);
        $this->epayco->hooks->order->registerAdminOrderTotalsAfterTotal([$this, 'registerInstallmentsFeeOnAdminOrder']);

        $this->epayco->hooks->endpoints->registerApiEndpoint(self::WEBHOOK_API_NAME, [$this, 'webhook']);
        $this->epayco->hooks->checkout->registerReceipt($this->id, [$this, 'renderOrderForm']);

        $this->epayco->hooks->cart->registerCartCalculateFees([$this, 'registerDiscountAndCommissionFeesOnCart']);

        $this->epayco->helpers->currency->handleCurrencyNotices($this);
    }

    /**
     * Get checkout name
     *
     * @return string
     */
    public function getCheckoutName(): string
    {
        return self::CHECKOUT_NAME;
    }

    /**
     * Init form fields for checkout configuration
     *
     * @return void
     */
    public function init_form_fields(): void
    {
        if ($this->addMissingCredentialsNoticeAsFormField()) {
            return;
        }

        parent::init_form_fields();

        $this->form_fields = array_merge($this->form_fields, [
            'header' => [
                'type'        => 'ep_config_title',
                'title'       => $this->adminTranslations['header_title'],
                'description' => $this->adminTranslations['header_description'],
            ],
            'enabled' => [
                'type'         => 'ep_toggle_switch',
                'title'        => $this->adminTranslations['enabled_title'],
                'subtitle'     => $this->adminTranslations['enabled_subtitle'],
                'default'      => 'no',
                'descriptions' => [
                    'enabled'  => $this->adminTranslations['enabled_descriptions_enabled'],
                    'disabled' => $this->adminTranslations['enabled_descriptions_disabled'],
                ],
            ],
            'title' => [
                'type'        => 'text',
                'title'       => $this->adminTranslations['title_title'],
                'description' => $this->adminTranslations['title_description'],
                'default'     => $this->adminTranslations['title_default'],
                'desc_tip'    => $this->adminTranslations['title_desc_tip'],
                'class'       => 'limit-title-max-length',
            ],
            'card_info_helper' => [
                'type'  => 'title',
                'value' => '',
            ]
        ]);
    }

    /**
     * Added gateway scripts
     *
     * @param string $gatewaySection
     *
     * @return void
     */
    public function payment_scripts(string $gatewaySection): void
    {
        parent::payment_scripts($gatewaySection);

        if ($this->canCheckoutLoadScriptsAndStyles()) {
            $this->registerCheckoutScripts();
        }
    }

    /**
     * Register checkout scripts
     *
     * @return void
     */
    public function registerCheckoutScripts(): void
    {
        parent::registerCheckoutScripts();

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_sdk',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/custom/library', '.js')
        );

        $this->epayco->hooks->scripts->registerCheckoutScript(
            'wc_epayco_custom_checkout',
            $this->epayco->helpers->url->getPluginFileUrl('assets/js/checkouts/custom/ep-custom-checkout', '.js'),
            [
                'public_key_epayco'        => $this->epayco->sellerConfig->getCredentialsPublicKeyPayment()
            ]
        );
    }

    /**
     * Render gateway checkout template
     *
     * @return void
     */
    public function payment_fields(): void
    {
        $this->epayco->hooks->template->getWoocommerceTemplate(
            'public/checkouts/custom-checkout.php',
            $this->getPaymentFieldsParams()
        );
    }

    /**
     * Get Payment Fields params
     *
     * @return array
     */
    public function getPaymentFieldsParams(): array
    {
        $amountAndCurrencyRatio = $this->getAmountAndCurrency();
        return [
            'test_mode'                        => $this->epayco->storeConfig->isTestMode(),
            'test_mode_title'                  => $this->storeTranslations['test_mode_title'],
            'test_mode_description'            => $this->storeTranslations['test_mode_description'],
            'test_mode_link_text'              => $this->storeTranslations['test_mode_link_text'],
            'test_mode_link_src'               => $this->links['docs_integration_test'],
            'wallet_button'                    => $this->epayco->hooks->options->getGatewayOption($this, 'wallet_button', 'yes'),
            'wallet_button_image'              => $this->epayco->helpers->url->getPluginFileUrl("assets/images/icons/icon-logos", '.png', true),
            'wallet_button_title'              => $this->storeTranslations['wallet_button_title'],
            'wallet_button_description'        => $this->storeTranslations['wallet_button_description'],
            'wallet_button_button_text'        => $this->storeTranslations['wallet_button_button_text'],
            'available_payments_title_icon'    => $this->epayco->helpers->url->getPluginFileUrl("assets/images/icons/icon-purple-card", '.png', true),
            'available_payments_title'         => $this->storeTranslations['available_payments_title'],
            'available_payments_image'         => $this->epayco->helpers->url->getPluginFileUrl("assets/images/checkouts/custom/chevron-down", '.png', true),
            'available_payments_chevron_up'    => $this->epayco->helpers->url->getPluginFileUrl("assets/images/checkouts/custom/chevron-up", '.png', true),
            'available_payments_chevron_down'  => $this->epayco->helpers->url->getPluginFileUrl("assets/images/checkouts/custom/chevron-down", '.png', true),
            'payment_methods_items'            => wp_json_encode($this->getPaymentMethodsContent()),
            'payment_methods_promotion_link'   => $this->links['epayco_debts'],
            'payment_methods_promotion_text'   => $this->storeTranslations['payment_methods_promotion_text'],
            'site_id'                          => $this->epayco->sellerConfig->getSiteId() ?: $this->epayco->helpers->country::SITE_ID_MLA,
            'card_form_title'                  => $this->storeTranslations['card_form_title'],
            'card_customer_title'              => $this->storeTranslations['card_customer_title'],
            'card_number_input_label'          => $this->storeTranslations['card_number_input_label'],
            'card_number_input_helper'         => $this->storeTranslations['card_number_input_helper'],
            'card_holder_name_input_label'     => $this->storeTranslations['card_holder_name_input_label'],
            'card_holder_name_input_helper'    => $this->storeTranslations['card_holder_name_input_helper'],
            'card_holder_email_input_label'    => $this->storeTranslations['card_holder_email_input_label'],
            'card_holder_email_input_helper'   => $this->storeTranslations['card_holder_email_input_helper'],
            'card_holder_email_input_invalid'   => $this->storeTranslations['input_helper_message_card_holder_email_316'],
            'card_holder_address_input_label'   => $this->storeTranslations['card_holder_address_input_label'],
            'card_holder_address_input_helper'  => $this->storeTranslations['card_holder_address_input_helper'],
            'card_expiration_input_label'      => $this->storeTranslations['card_expiration_input_label'],
            'card_expiration_input_helper'     => $this->storeTranslations['card_expiration_input_helper'],
            'card_expiration_input_invalid_length' => $this->storeTranslations['input_helper_message_expiration_date_invalid_value'],
            'card_security_code_input_label'   => $this->storeTranslations['card_security_code_input_label'],
            'card_security_code_input_helper'  => $this->storeTranslations['card_security_code_input_helper'],
            'card_security_code_input_invalid_length' => $this->storeTranslations['input_helper_message_security_code_invalid_length'],
            'input_ind_phone_label'            => $this->storeTranslations['card_cellphone_input_label'],
            'input_ind_phone_helper'           => $this->storeTranslations['card_cellphone_input_helper'],
            'card_document_input_label'        => $this->storeTranslations['card_document_input_label'],
            'card_document_input_helper'       => $this->storeTranslations['card_document_input_helper'],
            'card_installments_title'          => $this->storeTranslations['card_installments_title'],
            'card_issuer_input_label'          => $this->storeTranslations['card_issuer_input_label'],
            'card_installments_input_helper'   => $this->storeTranslations['card_installments_input_helper'],
            'card_holder_cellphone_input_label'   => $this->storeTranslations['card_cellphone_input_label'],
            'card_holder_cellphone_input_helper'  => $this->storeTranslations['card_cellphone_input_helper'],
            'terms_and_conditions_label'       => $this->storeTranslations['terms_and_conditions_label'],
            'terms_and_conditions_description' => $this->storeTranslations['terms_and_conditions_description'],
            'terms_and_conditions_link_text'   => $this->storeTranslations['terms_and_conditions_link_text'],
            'terms_and_conditions_link_src'    => $this->links['epayco_terms_and_conditions'],
            'amount'                           => $amountAndCurrencyRatio['amount'],
            'currency_ratio'                   => $amountAndCurrencyRatio['currencyRatio'],
            'message_error_amount'             => $this->storeTranslations['message_error_amount'],
        ];
    }

    /**
     * Process payment and create woocommerce order
     *
     * @param $order_id
     *
     * @return array
     */
    public function process_payment($order_id): array
    {
        $order = wc_get_order($order_id);

        try {
            $checkout = $this->getCheckoutEpaycoCustom($order);

            parent::process_payment($order_id);

            $this->epayco->logs->file->info('Preparing to get response of custom checkout', self::LOG_SOURCE);

            $checkout['token'] = $checkout['cardTokenId'] ?? $checkout['cardtokenid'];
            if (
                !empty($checkout['token'])
            ) {
                $this->transaction = new CustomTransaction($this, $order, $checkout);
                $redirect_url =get_site_url() . "/";
                $redirect_url = add_query_arg( 'wc-api', self::WEBHOOK_API_NAME, $redirect_url );
                $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );
                $redirect_url = 'http://696c-2800-e2-580-61c-1b36-86bb-aec2-9a0b.ngrok-free.app/wordpress2/wordpress/?wc-api=WC_Epayco_Custom_Gateway&order_id='.$order_id;
                $confirm_url = $redirect_url.'&confirmation=1';
                $checkout['confirm_url'] = $confirm_url;
                $response = $this->transaction->createTcPayment($order_id, $checkout);
                $response = json_decode(json_encode($response), true);
                if (is_array($response) && $response['success']) {
                    if (in_array(strtolower($response['data']['estado']),["pendiente","pending"])) {
                        $order->update_status("on-hold");
                        $this->epayco->woocommerce->cart->empty_cart();
                        //$this->epayco->hooks->order->addOrderNote($order, $this->storeTranslations['customer_not_paid']);
                        $urlReceived = $order->get_checkout_order_received_url();
                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];
                    }
                    if (in_array(strtolower($response['data']['estado']),["aceptada","acepted"])) {
                        $order->update_status("processing");
                        $this->epayco->woocommerce->cart->empty_cart();
                        $urlReceived = $order->get_checkout_order_received_url();
                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];
                    }if (in_array(strtolower($response['data']['estado']),["rechazada","fallida","cancelada","abandonada"])) {
                        $urlReceived = wc_get_checkout_url();
                        $return = [
                            'result'   => 'fail',
                            'message' => $response['data']['respuesta'],
                            'redirect' => $urlReceived,
                        ];
                    }
                    return $return;
                }else{
                    $messageError = $response['message'];
                    $errorMessage = "";
                    if (isset($response['data']['errors'])) {
                        $errors = $response['data']['errors'];
                        foreach ($errors as $error) {
                            $errorMessage = $error['errorMessage'] . "\n";
                        }
                    } elseif (isset($response['data']['error']['errores'])) {
                        $errores = $response['data']['error']['errores'];
                        foreach ($errores as $error) {
                            $errorMessage = $error['errorMessage'] . "\n";
                        }
                    }
                    return [
                        'result'   => 'fail',
                        'redirect' => '',
                        'message'  => $messageError. " " . $errorMessage,
                    ];
                }

                $this->epayco->orderMetadata->setCustomMetadata($order, $response);

                return $this->handleResponseStatus($order, $response, $checkout);
            }

            throw new InvalidCheckoutDataException('exception : Unable to process payment on ' . __METHOD__);

        } catch (\Exception $e) {
            return $this->processReturnFail(
                $e,
                $e->getMessage(),
                self::LOG_SOURCE,
                (array) $order,
                true
            );
        }
    }

    /**
     * Get checkout epayco custom
     *
     * @param $order
     *
     * @return array
     */
    private function getCheckoutEpaycoCustom($order): array
    {
        $checkout = [];

        if (isset($_POST['epayco_custom'])) {
            $checkout = Form::sanitizedPostData('epayco_custom');
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "no");
        } else {
            $checkout = $this->processBlocksCheckoutData('epayco_custom', Form::sanitizedPostData());
            $this->epayco->orderMetadata->markPaymentAsBlocks($order, "yes");
        }

        return $checkout;
    }


    /**
     * Get payment methods to fill in the available payments content
     *
     * @return array
     */
    private function getPaymentMethodsContent(): array
    {
        $debitCard      = [];
        $creditCard     = [];
        $paymentMethods = [];
        $cards          = $this->epayco->sellerConfig->getCheckoutBasicPaymentMethods();

        foreach ($cards as $card) {
            switch ($card['type']) {
                case 'credit_card':
                    $creditCard[] = [
                        'src' => $card['image'],
                        'alt' => $card['name'],
                    ];
                    break;

                case 'debit_card':
                case 'prepaid_card':
                    $debitCard[] = [
                        'src' => $card['image'],
                        'alt' => $card['name'],
                    ];
                    break;

                default:
                    break;
            }
        }

        if (count($creditCard) != 0) {
            $paymentMethods[] = [
                'title'           => $this->storeTranslations['available_payments_credit_card_title'],
                'label'           => $this->storeTranslations['available_payments_credit_card_label'],
                'payment_methods' => $creditCard,
            ];
        }

        if (count($debitCard) != 0) {
            $paymentMethods[] = [
                'title'           => $this->storeTranslations['available_payments_debit_card_title'],
                'payment_methods' => $debitCard,
            ];
        }

        return $paymentMethods;
    }

    /**
     * Render order form
     *
     * @param $orderId
     *
     * @return void
     * @throws \Exception
     */
    public function renderOrderForm($orderId): void
    {
        if ($this->epayco->helpers->url->validateQueryVar('wallet_button')) {
            $order             = wc_get_order($orderId);
            $this->transaction = new WalletButtonTransaction($this, $order);
            $preference        = $this->transaction->createPreference();

            $this->epayco->hooks->template->getWoocommerceTemplate(
                'public/receipt/preference-modal.php',
                [
                    'public_key'        => $this->epayco->sellerConfig->getCredentialsPublicKey(),
                    'preference_id'     => $preference['id'],
                    'pay_with_mp_title' => $this->storeTranslations['wallet_button_order_receipt_title'],
                    'cancel_url'        => $order->get_cancel_order_url(),
                    'cancel_url_text'   => $this->storeTranslations['cancel_url_text'],
                ]
            );
        }
    }

    /**
     * Render thank you page
     *
     * @param $order_id
     */
    public function renderInstallmentsRateDetails($order_id): void
    {
        $order             = wc_get_order($order_id);
        $currency          = $this->countryConfigs['currency_symbol'];
        $installments      = (float) $this->epayco->orderMetadata->getInstallmentsMeta($order);
        $installmentAmount = $this->epayco->orderMetadata->getTransactionDetailsMeta($order);
        $transactionAmount = Numbers::makesValueSafe($this->epayco->orderMetadata->getTransactionAmountMeta($order));
        $totalPaidAmount   = Numbers::makesValueSafe($this->epayco->orderMetadata->getTotalPaidAmountMeta($order));
        $totalDiffCost     = (float) $totalPaidAmount - (float) $transactionAmount;

        if ($totalDiffCost > 0) {
            $this->epayco->hooks->template->getWoocommerceTemplate(
                'public/order/custom-order-received.php',
                [
                    'title_installment_cost'  => $this->storeTranslations['title_installment_cost'],
                    'title_installment_total' => $this->storeTranslations['title_installment_total'],
                    'text_installments'       => $this->storeTranslations['text_installments'],
                    'total_paid_amount'       => Numbers::formatWithCurrencySymbol($currency, $totalPaidAmount),
                    'transaction_amount'      => Numbers::formatWithCurrencySymbol($currency, $transactionAmount),
                    'total_diff_cost'         => Numbers::formatWithCurrencySymbol($currency, $totalDiffCost),
                    'installment_amount'      => Numbers::formatWithCurrencySymbol($currency, $installmentAmount),
                    'installments'            => Numbers::format($installments),
                ]
            );
        }
    }

    /**
     * Handle with response status
     * The order_pay page always redirect the requester, so we must stop the current execution to return a JSON.
     * See ep-custom-checkout.js to understand how to handle the return.
     *
     * @param $return
     */
    private function handlePayForOrderRequest($return)
    {
        if (!headers_sent()) {
            header('Content-Type: application/json;');
        }
        echo wp_json_encode($return);
        die();
    }

    /**
     * Check if there is a pay_for_order query param.
     * This indicates that the user is on the Order Pay Checkout page.
     *
     * @return bool
     */
    private function isOrderPayPage(): bool
    {
        return $this->epayco->helpers->url->validateGetVar('pay_for_order');
    }

    /**
     * Handle with response status
     *
     * @param $order
     * @param $response
     * @param $checkout
     *
     * @return array
     */
    private function handleResponseStatus($order, $response): array
    {
        try {
            if (is_array($response) && array_key_exists('status', $response)) {
                switch ($response['status']) {
                    case 'approved':
                        $this->epayco->helpers->cart->emptyCart();

                        $urlReceived = $order->get_checkout_order_received_url();
                        $orderStatus = $this->epayco->orderStatus->getOrderStatusMessage('accredited');

                        $this->epayco->helpers->notices->storeApprovedStatusNotice($orderStatus);
                        $this->epayco->orderStatus->setOrderStatus($order, 'failed', 'pending');

                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];

                        if ($this->isOrderPayPage()) {
                            $this->handlePayForOrderRequest($return);
                        }

                        return $return;

                    case 'pending':
                    case 'in_process':
                        $statusDetail = $response['status_detail'];

                        if ($statusDetail === 'pending_challenge') {
                            $this->epayco->helpers->session->setSession('ep_3ds_url', $response['three_ds_info']['external_resource_url']);
                            $this->epayco->helpers->session->setSession('ep_3ds_creq', $response['three_ds_info']['creq']);
                            $this->epayco->helpers->session->setSession('ep_order_id', $order->ID);
                            $this->epayco->helpers->session->setSession('ep_payment_id', $response['id']);
                            $lastFourDigits = (empty($response['card']['last_four_digits'])) ? '****' : $response['card']['last_four_digits'];

                            $return = [
                                'result'           => 'success',
                                'three_ds_flow'    => true,
                                'last_four_digits' =>  $lastFourDigits,
                                'redirect'         => false,
                                'messages'         => '<script>load3DSFlow(' . $lastFourDigits . ');</script>',
                            ];

                            if ($this->isOrderPayPage()) {
                                $this->handlePayForOrderRequest($return);
                            }

                            return $return;
                        }

                        $this->epayco->helpers->cart->emptyCart();

                        $urlReceived = $order->get_checkout_order_received_url();

                        $return = [
                            'result'   => 'success',
                            'redirect' => $urlReceived,
                        ];

                        if ($this->isOrderPayPage()) {
                            $this->handlePayForOrderRequest($return);
                        }

                        return $return;

                    case 'rejected':
                        $errorMessage = $this->getRejectedPaymentErrorMessage($response['status_detail']);

                        if ($this->isOrderPayPage()) {
                            $this->handlePayForOrderRequest(array('result'   => 'fail', 'messages'  => $errorMessage));
                        }

                        $this->handleWithRejectPayment($response);
                        // Fall-through intentional - throw RejectedPaymentException for 'rejected' case.

                    default:
                        break;
                }
            }
            throw new ResponseStatusException('exception: Response status not mapped on ' . __METHOD__);
        } catch (\Exception $e) {
            return $this->processReturnFail(
                $e,
                $e->getMessage(),
                self::LOG_SOURCE,
                (array) $response,
                true
            );
        }
    }

    /**
     * Register installments fee on admin order totals
     *
     * @param int $orderId
     *
     * @return void
     */
    public function registerInstallmentsFeeOnAdminOrder(int $orderId): void
    {
        $order = wc_get_order($orderId);

        $currency    = $this->epayco->helpers->currency->getCurrencySymbol();
        $usedGateway = $this->epayco->orderMetadata->getUsedGatewayData($order);

        if ($this::ID === $usedGateway) {
            $totalPaidAmount       = Numbers::format(Numbers::makesValueSafe($this->epayco->orderMetadata->getTotalPaidAmountMeta($order)));
            $transactionAmount     = Numbers::format(Numbers::makesValueSafe($this->epayco->orderMetadata->getTransactionAmountMeta($order)));
            $installmentsFeeAmount = $totalPaidAmount - $transactionAmount;

            if ($installmentsFeeAmount > 0) {
                $this->epayco->hooks->template->getWoocommerceTemplate(
                    'admin/order/generic-note.php',
                    [
                        'tip'   => $this->epayco->adminTranslations->order['order_note_installments_fee_tip'],
                        'title' => $this->epayco->adminTranslations->order['order_note_installments_fee_title'],
                        'value' => Numbers::formatWithCurrencySymbol($currency, $installmentsFeeAmount),
                    ]
                );

                $this->epayco->hooks->template->getWoocommerceTemplate(
                    'admin/order/generic-note.php',
                    [
                        'tip'   => $this->epayco->adminTranslations->order['order_note_total_paid_amount_tip'],
                        'title' => $this->epayco->adminTranslations->order['order_note_total_paid_amount_title'],
                        'value' => Numbers::formatWithCurrencySymbol($currency, $totalPaidAmount),
                    ]
                );
            }
        }
    }
}
