<?php

namespace App\Enums;

enum GenderValues: string
{
    case UNKNOWN = 'unknown';
    case MALE = 'male';
    case FEMALE = 'female';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
