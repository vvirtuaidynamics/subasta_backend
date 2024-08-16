<?php

namespace App\Enums;

enum Components: string
{
    case AvatarField = 'AvatarField';
    case TextField = 'TextField';
    case TextAreaField = 'TextAreaField';
    case EditorField = 'EditorField';
    case PhoneField = 'PhoneField';
    case PasswordField = 'PasswordField';
    case NumberField = 'NumberField';
    case FileField = 'FileField';
    case DateField = 'DateField';
    case RadioField = 'RadioField';
    case BooleanField = 'BooleanField';
    case CheckField = 'CheckField';
    case RangeField = 'RangeField';
    case SelectField = 'SelectField';
    case HiddenField = 'HiddenField';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
