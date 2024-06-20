<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

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

if (!function_exists('get_models')) {
    function get_models($model_name="")
    {
        $models = array_map('basename', glob(app_path() . '/Models/*.php'));
        $schemas = [];

        foreach ($models as $model) {
            $model = explode('.', $model)[0];
            $modelClass = app('App\\Models\\' . $model);
            $table = (new $modelClass)->getTable();
            $columns = Schema::getColumnListing($table);

            if ($modelClass instanceof \Illuminate\Database\Eloquent\Model) {
                $model_fields = [];
                foreach ($columns as $column) {
                    $type = Schema::getColumnType($table, $column);

                    $model_fields[] = ["column" => $column, "type" => $type];
                }
                $schemas[] = ["name" => $model, "fields" => $model_fields];
                if($model_name && $model_name==$model){
                    return ["column" => $column, "type" => $type];
                }
            }
        }
        return $schemas;
    }
}

