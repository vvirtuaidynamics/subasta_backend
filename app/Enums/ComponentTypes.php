<?php

namespace App\Enums;

enum ComponentTypes: string
{
    case Text = 'text';
    case LongText = 'long-text';
    case TextEditor = 'text-editor';
    case Boolean = 'boolean';
    case Number = 'number';
    case Date = 'date';
    case Time = 'time';
    case DateTime = 'date-time';
    case Json = 'json';
    case File = 'file';
    case Image = 'image';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
