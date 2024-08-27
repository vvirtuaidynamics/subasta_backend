<?php

namespace App\Enums;

enum FormTypes: string
{
    case form = 'form';
    case stepForm = 'step-form';
    case dialogForm = 'dialog-form';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
