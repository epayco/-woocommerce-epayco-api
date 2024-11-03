<?php

namespace Epayco\Woocommerce\Helpers;

use Epayco\Woocommerce\Hooks\Admin;
use Epayco\Woocommerce\Libraries\Logs\Logs;
use Epayco\Woocommerce\Hooks\Options;
use Epayco\Woocommerce\Gateways\CreditCardGateway;
use Epayco\Woocommerce\Gateways\BasicGateway;

if (!defined('ABSPATH')) {
    exit;
}

class CreditCardEnabled
{
    /**
     * @const
     */
    private const CREDITS_ACTIVATION_NEEDED = 'epayco_credits_activation_needed';

    /**
     * @const
     */
    private const ALREADY_ENABLE_BY_DEFAULT = 'epayco_already_enabled_by_default';

    /**
     * @var Admin
     */
    private $admin;

    /**
     * @var Logs
     */
    private $logs;

    /**
     * @var Options
     */
    private $options;

    /**
     * CreditCardEnabled constructor
     *
     * @param Admin $admin
     * @param Logs $logs
     * @param Options $options
     */
    public function __construct(
        Admin $admin,
        Logs $logs,
        Options $options
    ) {
        $this->admin   = $admin;
        $this->logs    = $logs;
        $this->options = $options;
    }

    /**
     * Set default CreditCardEnabled options when needed
     */
    public function setCreditsDefaultOptions(): void
    {
        if ($this->admin->isAdmin() && $this->options->get(self::CREDITS_ACTIVATION_NEEDED) !== 'no') {
            $this->options->set(self::CREDITS_ACTIVATION_NEEDED, 'yes');
            $this->options->set(self::ALREADY_ENABLE_BY_DEFAULT, 'no');
        }
    }

    /**
     * Enable credits on the first execution
     */
    public function enableCreditsAction(): void
    {
        $this->setCreditsDefaultOptions();

        try {
            if ($this->admin->isAdmin() && $this->options->get(self::CREDITS_ACTIVATION_NEEDED) === 'yes') {
                $this->options->set(self::CREDITS_ACTIVATION_NEEDED, 'no');

                $basicGateway   = new BasicGateway();
                $creditsGateway = new CreditCardGateway();

                if ($this->options->get(self::ALREADY_ENABLE_BY_DEFAULT) === 'no') {
                    if (
                        isset($creditsGateway->settings['already_enabled_by_default']) &&
                        $this->options->getGatewayOption($creditsGateway, 'already_enabled_by_default')
                    ) {
                        return;
                    }

                    if (
                        isset($basicGateway->settings['enabled']) &&
                        $this->options->getGatewayOption($basicGateway, 'enabled')  === 'yes' &&
                        $creditsGateway->isAvailable()
                    ) {
                        $creditsGateway->activeByDefault();
                        $this->options->set(self::ALREADY_ENABLE_BY_DEFAULT, 'yes');
                    }
                }

                $this->logs->file->info('Credits was activated automatically', __METHOD__);
            }
        } catch (\Exception $ex) {
            $this->logs->file->error(
                "ePayco gave error to enable Credits: {$ex->getMessage()}",
                __CLASS__
            );
        }
    }
}
