<?php
namespace App\Enums;

enum CarrierDocuments: string
{
    case TRANSPORTATION_CARD = 'transportation_card';
    case MERCHANDISE_INSURANCE = 'merchandise_insurance';
    case HIGH_SOCIAL_SECURITY = 'high_social_security';
    case PAYMENT_CURRENT = 'payment_current';
    case VEHICLE_INSURANCE = 'vehicle_insurance';
    case ITV = 'itv';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}



