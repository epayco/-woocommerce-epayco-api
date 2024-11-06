<?php

namespace Epayco\Woocommerce\Transactions;

use Epayco\Woocommerce\Sdk\Entity\Payment\Payment;
use Epayco\Woocommerce\Sdk\EpaycoSdk;
use Epayco\Woocommerce\Gateways\AbstractGateway;
use Epayco\Woocommerce\Helpers\Date;
use Epayco\Woocommerce\Helpers\Numbers;
use Epayco\Woocommerce\Helpers\NotificationType;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadata;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadataAddress;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadataUser;
use Epayco\Woocommerce\Entities\Metadata\PaymentMetadataCpp;
use Epayco\Woocommerce\WoocommerceEpayco;

abstract class AbstractTransaction
{

    /**
     * @var WoocommerceEpayco
     */
    protected $epayco;

    /**
     * @var EpaycoSdk
     */
    protected $sdk;

    /**
     * Transaction
     *
     * @var Payment
     */
    protected $transaction;

    /**
     * Gateway
     *
     * @var AbstractGateway
     */
    protected $gateway;

    /**
     * Order
     *
     * @var \WC_Order
     */
    protected $order;

    /**
     * Checkout data
     *
     * @var array
     */
    protected $checkout = null;

    /**
     * Country configs
     *
     * @var array
     */
    protected $countryConfigs;

    /**
     * @var float
     */
    protected $ratio;

    /**
     * @var float
     */
    protected $orderTotal;

    /**
     * @var array
     */
    protected $listOfItems;

    /**
     * Abstract Transaction constructor
     *
     * @param AbstractGateway $gateway
     * @param \WC_Order $order
     * @param array|null $checkout
     */
    public function __construct(AbstractGateway $gateway, \WC_Order $order, array $checkout = null)
    {
        global $epayco;

        $this->epayco      = $epayco;
        $this->order       = $order;
        $this->gateway     = $gateway;
        $this->checkout    = $checkout;
        $this->sdk         = $this->getSdkInstance();

        $this->ratio          = $this->epayco->helpers->currency->getRatio($gateway);
        $this->countryConfigs = $this->epayco->helpers->country->getCountryConfigs();

        $this->orderTotal     = 0;
    }

    /**
     * Get SDK instance
     */
    public function getSdkInstance():EpaycoSdk
    {
        $public_key = $this->epayco->sellerConfig->getCredentialsPublicKeyPayment();
        $private_key = $this->epayco->sellerConfig->getCredentialsPrivateKeyPayment();
        $pCustId = $this->epayco->sellerConfig->getCredentialsPCustId();
        $pKey = $this->epayco->sellerConfig->getCredentialsPkey();
        $isTestMode = $this->epayco->storeConfig->isTestMode()?"true":"false";
        $idioma = substr(get_locale(), 0, 2);
        return new EpaycoSdk([
            "apiKey" => $public_key,
            "privateKey" =>$private_key,
            "lenguage" => strtoupper($idioma),
            "test" => $isTestMode
        ],
        "",
            $public_key,
            $private_key,
            $pCustId,
            $pKey
        );



    }

    /**
     * Get transaction
     *
     * @param string $transactionType
     *
     * @return Payment
     */
    public function getTransaction(string $transactionType)
    {
        $transactionClone = clone $this->transaction;

        unset($transactionClone->token);

        return $this->transaction;
    }

    /**
     * Set common transaction
     *
     * @return void
     */
    public function setCommonTransaction(): void
    {
        $this->transaction->binary_mode          = $this->getBinaryMode();
        $this->transaction->external_reference   = $this->getExternalReference();
        $this->transaction->notification_url      = $this->getNotificationUrl();
        $this->transaction->metadata             = (array) $this->getInternalMetadata();
        $this->transaction->statement_descriptor = $this->epayco->storeConfig->getStoreName('Sdk');
    }

    /**
     * Get notification url
     *
     * @return string|void
     */
    private function getNotificationUrl()
    {
        $customDomain        = $this->epayco->storeConfig->getCustomDomain();
        $customDomainOptions = $this->epayco->storeConfig->getCustomDomainOptions();

        if (
            !empty($customDomain) && (
            strrpos($customDomain, 'localhost') === false ||
            filter_var($customDomain, FILTER_VALIDATE_URL) === false
            )
        ) {
            if ($customDomainOptions === 'yes') {
                return $customDomain . '?wc-api=' . $this->gateway::WEBHOOK_API_NAME . '&source_news=' . NotificationType::getNotificationType($this->gateway::WEBHOOK_API_NAME);
            } else {
                return $customDomain;
            }
        }

        if (empty($customDomain) && !strrpos(get_site_url(), 'localhost')) {
            $notificationUrl  = $this->epayco->woocommerce->api_request_url($this->gateway::WEBHOOK_API_NAME);
            $urlJoinCharacter = preg_match('#/wc-api/#', $notificationUrl) ? '?' : '&';

            return $notificationUrl . $urlJoinCharacter . 'source_news=' . NotificationType::getNotificationType($this->gateway::WEBHOOK_API_NAME);
        }
    }

    /**
     * Get binary mode
     *
     * @return bool
     */
    public function getBinaryMode(): bool
    {
        $binaryMode = $this->gateway
            ? $this->epayco->hooks->options->getGatewayOption($this->gateway, 'binary_mode', 'no')
            : 'no';

        return $binaryMode !== 'no';
    }

    /**
     * Get external reference
     *
     * @return string
     */
    public function getExternalReference(): string
    {
        return $this->epayco->storeConfig->getStoreId() . $this->order->get_id();
    }

    /**
     * Get internal metadata
     *
     * @return PaymentMetadata
     */
    public function getInternalMetadata(): PaymentMetadata
    {
        $seller  = $this->epayco->sellerConfig->getCollectorId();
        $siteId  = $this->epayco->sellerConfig->getSiteId();
        $siteUrl = $this->epayco->hooks->options->get('siteurl');

        $zipCode = $this->epayco->orderBilling->getZipcode($this->order);
        $zipCode = str_replace('-', '', $zipCode);

        $user             = $this->epayco->helpers->currentUser->getCurrentUser();
        $userId           = $user->ID;
        $userRegistration = $user->user_registered;

        $metadata = new PaymentMetadata();
        $metadata->platform                      = EP_PLATFORM_ID;
        $metadata->platform_version              = $this->epayco->woocommerce->version;
        $metadata->module_version                = EP_VERSION;
        $metadata->php_version                   = PHP_VERSION;
        $metadata->site_id                       = strtolower($siteId);
        $metadata->sponsor_id                    = $this->countryConfigs['sponsor_id'];
        $metadata->collector                     = $seller;
        $metadata->test_mode                     = $this->epayco->storeConfig->isTestMode();
        $metadata->details                       = '';
        $metadata->seller_website                = $siteUrl;
        $metadata->billing_address               = new PaymentMetadataAddress();
        $metadata->billing_address->zip_code     = $zipCode;
        $metadata->billing_address->street_name  = $this->epayco->orderBilling->getAddress1($this->order);
        $metadata->billing_address->city_name    = $this->epayco->orderBilling->getCity($this->order);
        $metadata->billing_address->state_name   = $this->epayco->orderBilling->getState($this->order);
        $metadata->billing_address->country_name = $this->epayco->orderBilling->getCountry($this->order);
        $metadata->user                          = new PaymentMetadataUser();
        $metadata->user->registered_user         = $userId ? 'yes' : 'no';
        $metadata->user->user_email              = $userId ? $user->user_email : null;
        $metadata->user->user_registration_date  = $userId ? Date::formatGmDate($userRegistration) : null;
        $metadata->cpp_extra                     = new PaymentMetadataCpp();
        $metadata->cpp_extra->platform_version   = $this->epayco->woocommerce->version;
        $metadata->cpp_extra->module_version     = EP_VERSION;
        $metadata->blocks_payment                = $this->epayco->orderMetadata->getPaymentBlocks($this->order);
        $metadata->settings                      = $this->epayco->metadataConfig->getGatewaySettings($this->gateway::ID);
        $metadata->auto_update                   = $this->epayco->sellerConfig->isAutoUpdate();
        return $metadata;
    }

    /**
     * Set additional shipments information
     *
     * @param $shipments
     *
     * @return void
     */
    public function setShipmentsTransaction($shipments): void
    {
        $shipments->receiver_address->street_name = $this->epayco->orderShipping->getAddress1($this->order);
        $shipments->receiver_address->zip_code    = $this->epayco->orderShipping->getZipcode($this->order);
        $shipments->receiver_address->city        = $this->epayco->orderShipping->getCity($this->order);
        $shipments->receiver_address->state       = $this->epayco->orderShipping->getState($this->order);
        $shipments->receiver_address->country     = $this->epayco->orderShipping->getCountry($this->order);
        $shipments->receiver_address->apartment   = $this->epayco->orderShipping->getAddress2($this->order);
    }

    /**
     * Set items on transaction
     *
     * @param $items
     *
     * @return void
     */
    public function setItemsTransaction($items): void
    {
        foreach ($this->order->get_items() as $item) {
            $product  = $item->get_product();
            $quantity = $item->get_quantity();

            $title = $product->get_name();
            $title = "$title x $quantity";

            $amount = $this->getItemAmount($item);

            $this->orderTotal   += $amount;
            $this->listOfItems[] = $title;

            $item = [
                'id'          => $item->get_product_id(),
                'title'       => $title,
                'description' => $this->epayco->helpers->strings->sanitizeAndTruncateText($product->get_description()),
                'picture_url' => $this->getItemImage($product),
                'category_id' => $this->epayco->storeConfig->getStoreCategory('others'),
                'unit_price'  => $amount,
                'currency_id' => $this->countryConfigs['currency'],
                'quantity'    => 1,
            ];

            $items->add($item);
        }
    }

    /**
     * Set shipping
     *
     * @param $items
     *
     * @return void
     */
    public function setShippingTransaction($items): void
    {
        $shipTotal = Numbers::format((float) $this->order->get_shipping_total());
        $shipTaxes = Numbers::format((float) $this->order->get_shipping_tax());

        $amount = $shipTotal + $shipTaxes;
        $amount = Numbers::calculateByCurrency($this->countryConfigs['currency'], $amount, $this->ratio);

        if ($amount > 0) {
            $this->orderTotal += $amount;

            $item = [
                'id'          => 'shipping',
                'title'       => $this->epayco->orderShipping->getShippingMethod($this->order),
                'description' => $this->epayco->storeTranslations->commonCheckout['shipping_title'],
                'category_id' => $this->epayco->storeConfig->getStoreCategory('others'),
                'unit_price'  => $amount,
                'currency_id' => $this->countryConfigs['currency'],
                'quantity'    => 1,
            ];

            $items->add($item);
        }
    }

    /**
     * Set fee
     *
     * @param $items
     *
     * @return void
     */
    public function setFeeTransaction($items): void
    {
        foreach ($this->order->get_fees() as $fee) {
            $feeTotal = Numbers::format((float) $fee->get_total());
            $feeTaxes = Numbers::format((float) $fee->get_total_tax());

            $amount = $feeTotal + $feeTaxes;
            $amount = Numbers::calculateByCurrency($this->countryConfigs['currency'], $amount, $this->ratio);

            $this->orderTotal += $amount;

            $item = [
                'id'          => 'fee',
                'title'       => $this->epayco->helpers->strings->sanitizeAndTruncateText($fee['name']),
                'description' => $this->epayco->helpers->strings->sanitizeAndTruncateText($fee['name']),
                'category_id' => $this->epayco->storeConfig->getStoreCategory('others'),
                'unit_price'  => $amount,
                'currency_id' => $this->countryConfigs['currency'],
                'quantity'    => 1,
            ];

            $items->add($item);
        }
    }

    /**
     * Get item amount
     *
     * @param \WC_Order_Item|\WC_Order_Item_Product $item
     *
     * @return float
     */
    public function getItemAmount(\WC_Order_Item $item): float
    {
        $lineAmount = $item->get_total() + $item->get_total_tax();
        return Numbers::calculateByCurrency($this->countryConfigs['currency'], $lineAmount, $this->ratio);
    }

    /**
     * Get item image
     *
     * @param mixed $product
     *
     * @return string
     */
    public function getItemImage($product): string
    {
        return is_object($product) && method_exists($product, 'get_image_id')
            ? wp_get_attachment_url($product->get_image_id())
            : $this->epayco->helpers->url->getPluginFileUrl('assets/images/gateways/all/blue-cart', '.png', true);
    }

    /**
     * Set additional info
     *
     * @return void
     */
    public function setAdditionalInfoTransaction(): void
    {
        $this->setAdditionalInfoBaseInfoTransaction();
        $this->setAdditionalInfoItemsTransaction();
        $this->setAdditionalInfoShipmentsTransaction();
        $this->setAdditionalInfoPayerTransaction();
        $this->setAdditionalInfoSellerTransaction();
    }

    /**
     * Set base information
     *
     * @return void
     */
    public function setAdditionalInfoBaseInfoTransaction(): void
    {
        $this->transaction->additional_info->ip_address = $this->epayco->helpers->url->getServerAddress();
        $this->transaction->additional_info->referral_url = $this->epayco->helpers->url->getBaseUrl();
    }

    /**
     * Set additional items information
     *
     * @return void
     */
    public function setAdditionalInfoItemsTransaction(): void
    {
        $items = $this->transaction->additional_info->items;

        $this->setItemsTransaction($items);
        $this->setShippingTransaction($items);
        $this->setFeeTransaction($items);
    }

    /**
     * Set additional shipments information
     *
     * @return void
     */
    public function setAdditionalInfoShipmentsTransaction(): void
    {
        $this->setShipmentsTransaction($this->transaction->additional_info->shipments);
    }

    /**
     * Set additional seller information
     *
     * @return void
     */
    public function setAdditionalInfoSellerTransaction(): void
    {
        $seller = $this->transaction->additional_info->seller;

        $seller->store_id      = $this->epayco->storeConfig->getStoreId();
        $seller->business_type = $this->epayco->storeConfig->getStoreCategory('others');
        $seller->collector     = $this->epayco->sellerConfig->getClientId();
        $seller->website       = $this->epayco->helpers->url->getBaseUrl();
        $seller->platform_url  = $this->epayco->helpers->url->getBaseUrl();
        $seller->referral_url  = $this->epayco->helpers->url->getBaseUrl();
    }

    /**
     * Set additional payer information
     *
     * @return void
     */
    public function setAdditionalInfoPayerTransaction(): void
    {
        $payer = $this->transaction->additional_info->payer;

        $payer->first_name           = $this->epayco->orderBilling->getFirstName($this->order);
        $payer->last_name            = $this->epayco->orderBilling->getLastName($this->order);
        $payer->user_email           = $this->epayco->orderBilling->getEmail($this->order);
        $payer->phone->number        = $this->epayco->orderBilling->getPhone($this->order);
        $payer->mobile->number       = $this->epayco->orderBilling->getPhone($this->order);
        $payer->address->city        = $this->epayco->orderBilling->getCity($this->order);
        $payer->address->state       = $this->epayco->orderBilling->getState($this->order);
        $payer->address->country     = $this->epayco->orderBilling->getCountry($this->order);
        $payer->address->zip_code    = $this->epayco->orderBilling->getZipcode($this->order);
        $payer->address->street_name = $this->epayco->orderBilling->getAddress1($this->order);
        $payer->address->apartment   = $this->epayco->orderBilling->getAddress2($this->order);

        if ($this->epayco->helpers->currentUser->isUserLoggedIn()) {
            $payer->registered_user        = true;
            $payer->identification->number = $this->epayco->helpers->currentUser->getCurrentUserMeta('billing_document', true);
            $payer->registration_date      = $this->epayco->helpers->currentUser->getCurrentUserData()->user_registered;
            $payer->platform_email         = $this->epayco->helpers->currentUser->getCurrentUserData()->user_email;
            $payer->register_updated_at    = $this->epayco->helpers->currentUser->getCurrentUserData()->__get('user_modified');
        }
    }
}
