<?php

namespace Epayco\Woocommerce\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

final class NotificationType
{
    /**
     * Get Status Type
     *
     * @param $ClassGateway
     *
     * @return string
     */
    public static function getNotificationType($ClassGateway): string
    {
        $types['WC_Epayco_Basic_Gateway']   = 'ipn';
        $types['WC_Epayco_Credits_Gateway'] = 'ipn';
        $types['WC_Epayco_Custom_Gateway']  = 'webhooks';
        $types['WC_Epayco_Pix_Gateway']     = 'webhooks';
        $types['WC_Epayco_Ticket_Gateway']  = 'webhooks';
        $types['WC_Epayco_Pse_Gateway']  = 'webhooks';

        return $types[ $ClassGateway ];
    }
}
