<?php

namespace Epayco\Woocommerce\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

final class PaymentStatus
{
    /**
     * Get Status Type
     *
     * @param string $paymentStatus
     *
     * @return string
     */
    public static function getStatusType(string $paymentStatus): string
    {
        $paymentStatusMap = [
            'aceptada'      => 'success',
            'accepted'      => 'success',
            'pendiente'     => 'pending',
            'pending'       => 'pending',
            'rechazada'     => 'rejected',
            'rejected'      => 'rejected',
            'cancelada'     => 'rejected',
            'canceled'      =>  'rejected',
            'fallida'       =>  'rejected',
            'failed'        =>  'rejected',
            'refunded'     => 'refunded',
            'charged_back' => 'charged_back',
            'generic'      => 'rejected'
        ];

        return array_key_exists($paymentStatus, $paymentStatusMap)
            ? $paymentStatusMap[$paymentStatus]
            : $paymentStatusMap['generic'];
    }

    /**
     * Get Card Description
     *
     * @param $translationsArray
     * @param $paymentStatusDetail
     * @param $isCreditCard
     *
     * @return array
     */
    public static function getCardDescription($translationsArray, $paymentStatusDetail, $isCreditCard): array
    {
        $alertTitleTranslationKey  = 'alert_title_' . $paymentStatusDetail;
        $descriptionTranslationKey = 'description_' . $paymentStatusDetail;

        $alertTitle = array_key_exists($alertTitleTranslationKey, $translationsArray)
            ? $translationsArray[$alertTitleTranslationKey]
            : $translationsArray['alert_title_generic'];

        $description = array_key_exists($descriptionTranslationKey, $translationsArray)
            ? $translationsArray[$descriptionTranslationKey]
            : $translationsArray['description_generic'];

        $creditCardDescriptionKey = $descriptionTranslationKey . '_cc';
        if ($isCreditCard && array_key_exists($creditCardDescriptionKey, $translationsArray)) {
            $description = $translationsArray[$creditCardDescriptionKey];
        }

        return [
            'alert_title' => $alertTitle,
            'description' => $description,
        ];
    }
}
