<?php

use Illuminate\Support\Facades\App;

if (!function_exists('trans_lang_using_default_file')) {
    function trans_lang_using_default_file($key): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return trans('default.' . $key);
    }
}

if (!function_exists('trans_lang_using_key')) {
    function trans_lang_using_key($key): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return trans($key);
    }
}

if (!function_exists('datetime_format_string')) {
    function datetime_format_string(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        $currentLanguage = App::getLocale();
        if ($currentLanguage == "es")
            return 'd-m-Y H:i:s';
        return 'Y-m-d H:i:s';
    }
}

if (!function_exists('date_format_string')) {
    function date_format_string(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        $currentLanguage = App::getLocale();
        if ($currentLanguage == "es")
            return 'd-m-Y';
        return 'Y-m-d';
    }
}

