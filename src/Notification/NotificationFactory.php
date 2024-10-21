<?php

namespace Epayco\Woocommerce\Notification;

use Epayco\Woocommerce\Interfaces\EpaycoGatewayInterface;
use Epayco\Woocommerce\Interfaces\NotificationInterface;

if (!defined('ABSPATH')) {
    exit;
}

class NotificationFactory
{
    /**
     * Create a notification handler based on $data
     *
     * @param array $data data from $_GET
     *
     * @return void
     */
    public function createNotificationHandler(EpaycoGatewayInterface $gateway, array $data): NotificationInterface
    {
        global $epayco;

        $topic  = isset($data['topic']) ? $data['topic'] : '';
        $type   = isset($data['type']) ? $data['type'] : '';
        $source = isset($data['source_news']) ? $data['source_news'] : '';

        if ($type === 'payment' && $source === 'webhooks') {
            return new WebhookNotification(
                $gateway,
                $epayco->logs,
                $epayco->orderStatus,
                $epayco->sellerConfig,
                $epayco->storeConfig,
                $epayco->helpers->requester
            );
        }

        if ($topic === 'merchant_order' && $source === 'ipn') {
            return new IpnNotification(
                $gateway,
                $epayco->logs,
                $epayco->orderStatus,
                $epayco->sellerConfig,
                $epayco->storeConfig,
                $epayco->helpers->requester
            );
        }

        return new CoreNotification(
            $gateway,
            $epayco->logs,
            $epayco->orderStatus,
            $epayco->sellerConfig,
            $epayco->storeConfig
        );
    }
}
