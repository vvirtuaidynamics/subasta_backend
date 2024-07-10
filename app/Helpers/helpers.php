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
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\User;

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

if (!function_exists('format_datetime_for_database')) {

    function format_datetime_for_database($datetime)
    {
        if (!$datetime instanceof Carbon) {
            $datetime = Carbon::parse($datetime);
        }

        return $datetime->setTimezone('UTC')->format('Y-m-d H:i:s');
    }
}

if (!function_exists('format_datetime_for_display')) {

    function format_datetime_for_display($datetime, $format = null)
    {
        if (!$datetime instanceof Carbon) {
            $datetime = Carbon::parse($datetime);
        }

        $datetime->setTimezone(config('app.timezone'));

        if ($format) {
            return $datetime->translatedFormat($format);
        }

        $locale = App::getLocale();
        switch ($locale) {
            case 'es':
                return $datetime->translatedFormat('d-m-Y H:i:s');
            default:
                return $datetime->translatedFormat('Y-m-d H:i:s');
        }
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
                else
                    $class_namespace = "App\\Models\\$model";

                foreach ($columns as $column) {
                    $type = Schema::getColumnType($table, $column);

                    $model_fields[] = ["column" => $column, "type" => $type];
                }
                $model_schema = ["name" => $model, "class" => $class_namespace, "fields" => $model_fields];
                $schemas[] = $model_schema;
                if ($model_name && $model_name == $model) {
                    return $model_schema;
                }
            }
        }
        return $schemas;
    }
}
if (!function_exists('get_modules')) {
    function get_modules(): array
    {
        $modulos = config('modules.data');
        $models = get_models();
        $modules = [];
        if (isset($modulos) && is_array($modulos))
            foreach ($modulos as $modulo) {
                $modulo_model = null;
                $m = [
                    'name' => $modulo['name'],
                    'model' => $modulo['model'],
                    'class' => $modulo['class'],
                    'label' => $modulo['label'],
                    'title' => $modulo['title'],
                    'url' => $modulo['url'],
                    'icon' => $modulo['icon'],
                    'order' => $modulo['order'],
                    'parent' => $modulo['parent'],
                    'fields' => $modulo['fields'],
                    'permissions' => json_encode($modulo['permissions']),
                ];
                if ($m['model'] != '') {
                    $modulo_model = collect(array_filter($models, function ($model) use ($modulo) {
                        return strtolower($model['name']) === strtolower($modulo['name']);
                    }))->first();
                    if (isset($modulo_model)) {
                        if (isset($modulo_model['fields']) && $modulo_model['fields'] != '')
                            $m['fields'] = json_encode($modulo_model['fields']);
                        else {
                            $m['fields'] = "{}";
                        }
                        // Validate class
                        if (!isset($m['class']) || !class_exists($m['class'])) {
                            if (class_exists($modulo_model->class)) {
                                $m['class'] = $modulo_model->class;
                            }
                        }
                    }
                }
                $modules[] = $m;
            }
        return $modules;
    }
}

if (!function_exists('permissions_sync')) {
    function permissions_sync(): array
    {
        $modules = get_modules();
        $super_admin = \Spatie\Permission\Models\Role::findOrCreate(config('permission.super_admin_role_name'), 'api');
        $start_permissions = Permission::all();
        foreach ($modules as $module) {
            $name = $module['name'];
            $permissions = json_decode($module['permissions']);
            if (isset($permissions) && is_array($permissions))
                foreach ($permissions as $p) {
                    $permission = Permission::findOrCreate("$name:$p", 'api');
                    $super_admin->givePermissionTo($permission);
                }
        }
        $final_permissions = Permission::all();
        $diff_permissions = $final_permissions->diff($start_permissions);
        return [
            'permissions' => $final_permissions,
            'changes' => $diff_permissions,
            'total' => $final_permissions->count(),
            'diff' => $final_permissions->count() - $start_permissions->count(),
        ];
    }
}

if (!function_exists('roles_sync')) {
    function roles_sync(): array
    {
        $super_admin = Role::findOrCreate(config('permission.super_admin_role_name'), 'api');
        $start_roles = Role::all();
        $config_roles = config('permission.base_roles');
        foreach ($config_roles as $role) {
            $role_name = $role['name'];
            $rol = Role::findOrCreate($role_name, 'api');
            $permissions = $role['permissions'];
            if (isset($permissions) && is_array($permissions))
                //TODO: Se puede implementar lógica para usar comodines para modelos o permisos
                // Ejemplo:
                foreach ($permissions as $p) {
                    $permission = Permission::findOrCreate("$p", 'api');
                }
        }
        $final_roles = Role::all();
        $diff_roles = $final_roles->diff($start_roles);
        return [
            'roles' => $final_roles,
            'changes' => $diff_roles,
            'total' => $final_roles->count(),
            'diff' => $final_roles->count() - $start_roles->count(),
        ];
    }
}

if (!function_exists('get_user_modules')) {
    function get_user_modules(User $user = null): array
    {
        if (!$user) return [];
        $user_permissions = collect($user->getAllPermissions())->pluck('name')->toArray();
        $user_modules = [];
        $modules = get_modules();
        foreach ($modules as $module) {
            $name = $module['name'];
            $module_permissions = [];
            if (isset($module['permissions']) && $module['permissions'] != '' && $module['permissions']) {
                $module_permissions = json_decode($module['permissions']);
            }
            $permisions = [];
            $module_has_per = 0;
            foreach ($module_permissions as $p) {
                $pname = "$name:$p";
                $pval = ($user->super_admin || in_array("$name:$p", $user_permissions));
                if ($pval) $module_has_per++;
                $permisions[] = [$pname => $pval];
            }
            $module['permissions'] = $permisions;
            if ($module_has_per > 0)
                $user_modules[] = $module;
        }
        return $user_modules;
    }
}

