<?php
/**
 * Add to composer.json file on autoload section:
 *  "files": [
 *      "app/Helpers/helpers.php"
 *  ],
 */

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
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
    function get_models($model_name = ""): array
    {
        $models = array_map('basename', glob(app_path() . '/Models/*.php'));


        //Add spatie models
        $spatie_permission = ['Permission', 'Role'];
        $spatie_activity = ['Activity'];
        if (class_exists('Spatie\Permission\Models\Permission')) {
            $models[] = "Permission.php";
            $models[] = "Role.php";
        }
        if (class_exists('Spatie\Activitylog\Models\Activity')) {
            $models[] = "Activity.php";
        }

        $schemas = [];
        foreach ($models as $model) {
            $model = explode('.', $model)[0];
            if (in_array($model, $spatie_permission))
                $modelClass = app('Spatie\\Permission\\Models\\' . $model);
            elseif (in_array($model, $spatie_activity))
                $modelClass = app('Spatie\\Activitylog\\Models\\' . $model);
            else
                $modelClass = app('App\\Models\\' . $model);
            $table = (new $modelClass)->getTable();
            $columns = Schema::getColumnListing($table);


            if ($modelClass instanceof \Illuminate\Database\Eloquent\Model) {
                $model_fields = [];
                $class_name = class_basename($modelClass);
                if (in_array($model, $spatie_permission))
                    $class_namespace = "Spatie\\Permission\\Models\\$model";
                elseif (in_array($model, $spatie_activity))
                    $class_namespace = "Spatie\\Activitylog\\Models\\$model";
                else
                    $class_namespace = "App\\Models\\$model";

                foreach ($columns as $column) {
                    $type = Schema::getColumnType($table, $column);

                    $model_fields[] = ["column" => $column, "type" => $type];
                }
                $schemas[] = ["name" => $model, "namespace" => $class_namespace, "fields" => $model_fields];
                if ($model_name && $model_name == $model) {
                    return ["column" => $column, "type" => $type];
                }
            }


        }
        return $schemas;
    }
}

if (!function_exists('get_user_models')) {
    function get_user_models(\App\Models\User $user = null): array
    {
//        if (!$user) return [];

        $appList = collect([]);
        $applications = collect(DB::table('modules')->where('parent_name', '=', '')->orderBy('order', 'asc')->get());

        $application_names = collect($applications)->pluck('name')->toArray();
        foreach ($applications as $application) {
            $modules = DB::table('modules')->where('parent_name', '=', $application->name)->orderBy('order', 'asc')->get();
            dd($modules);
            $module_names = collect($modules)->pluck('name')->toArray();
            $app = $application;

            $app['children'] = [];
            foreach ($modules as $module) {
                if (!$user->is_super_admin) {
                    $module['permissions'] = ['create' => true, 'read' => true, 'update' => true, 'delete' => true];
                    $app['children'] = $module;
                }
                //todo continuar con la logica


            }


        }

        $actions_default = ['create', 'read', 'update', 'delete'];
        $actions_readonly = ['read'];

        $user_permissions = collect($user->getAllPermissions())->pluck('name')->toArray();

        $application = DB::table('modules')->where('parent_name', '!=', '')->orderBy('order', 'asc')->get();
        dd($application);
//        if ($application->count > 0) {
//            $app_models = DB::table('modules')->where('parent',)
//        }
//
//        if(){
//
//        }

        $schemas = [];


    }
}

