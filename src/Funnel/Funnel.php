<?php

namespace Epayco\Woocommerce\Funnel;

use Epayco\Woocommerce\Configs\Seller;
use Epayco\Woocommerce\Configs\Store;
use Epayco\Woocommerce\Helpers\Gateways;
use Epayco\Woocommerce\Helpers\Country;

class Funnel
{


    /**
     * @var Store
     */
    private $store;

    /**
     * @var Seller
     */
    private $seller;

    /**
     * @var Country
     */
    private $country;

    /**
     * @var Gateways
     */
    private $gateways;


    /**
     * Funnel constructor
     *
     * @param Store $store
     * @param Seller $seller
     * @param Country $country
     * @param Gateways $gateways
     */
    public function __construct(Store $store, Seller $seller, Country $country, Gateways $gateways)
    {
        $this->store    = $store;
        $this->seller   = $seller;
        $this->country  = $country;
        $this->gateways = $gateways;
    }

    public function getInstallationId(): void
    {
        if ($this->validateStartFunnel()) {

        }
    }

    public function updateStepCredentials(): void
    {
        if ($this->isInstallationId()) {

        }
    }

    /**
     * @param string $paymentMethod
     *
     * @return void
     */
    public function updateStepPaymentMethods(): void
    {
        if ($this->isInstallationId()) {

        }
    }

    public function updateStepPluginMode(): void
    {
        if ($this->isInstallationId()) {

        }
    }

    public function updateStepUninstall(): void
    {
        if ($this->isInstallationId()) {

        }
    }

    public function updateStepDisable(): void
    {
        if ($this->isInstallationId()) {
        }
    }

    public function updateStepActivate(): void
    {
        if ($this->isInstallationId()) {
            $this->runWithTreatment(function () {
                $this->store->setExecuteActivate('no');
            });
        }
    }

    public function isInstallationId(): bool
    {
        return !empty($this->store->getInstallationId())
        && !empty($this->store->getInstallationKey());
    }

    private function validateStartFunnel(): bool
    {
        return empty($this->seller->getCredentialsAccessTokenProd()) &&
            !$this->isInstallationId() &&
            empty($this->gateways->getEnabledPaymentGateways());
    }

    private function getPluginMode(): string
    {
        return $this->store->isProductionMode() ? 'Prod' : 'Test';
    }

    private function getWoocommerceVersion(): string
    {
        return $GLOBALS['woocommerce']->version ? $GLOBALS['woocommerce']->version : "";
    }

    private function runWithTreatment($callback)
    {
        try {
            $callback();

        } catch (\Exception $ex) {

        }
    }




}
