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

/**
 * replace non alphabetical letters with hyphen and convert to lower case
 * @method string slugify
 * @param string $text
 * @return string
 */
if (!function_exists('slugify')) {
    function slugify($text): string
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);
        return $text;
    }
}

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
        $searchables = config('modules.searchable_types');
        $filterables = config('modules.filterable_types');
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
                    $is_searchable = false;
                    $is_filterable = false;
                    if ($searchables['types'] && $searchables['column_exceptions']) {
                        if (in_array($type, $searchables['types']) && !in_array($column, $searchables['column_exceptions'])) {
                            $is_searchable = true;
                        }
                    }

                    if ($filterables['types'] && $filterables['column_exceptions']) {
                        if (in_array($type, $filterables['types']) && !in_array($column, $filterables['column_exceptions'])) {
                            $is_filterable = true;
                        }
                    }
                    $model_fields[] = ["column" => $column, "type" => $type, "searchable" => $is_searchable, "filterable" => $is_filterable];
                }

                $model_schema = ["name" => $model, "class" => $class_namespace, "fields" => $model_fields];
                $schemas[] = $model_schema;
                if ($model_name && strtolower($model_name) == strtolower($model)) {
                    return $model_schema;
                }
            }
        }
        return $schemas;
    }
}

if (!function_exists('get_forms_data')) {
    function get_forms_data(): array
    {
        $fields = config('fields.default');
        $forms = config('forms.default');
        $forms_data = [];
        foreach ($forms as $form) {
            $form_item = [
                'name' => $form['name'],
                'module_name' => $form['module_name'],
                'type' => $form['type'],
                'label' => $form['label'],
                'route' => $form['route'],
                'options' => $form['options'],
                'default_value' => $form['default_value'] ?? '{}',
            ];
            $fields_of_form = $form['fields'];
            $fields_data = [];
            foreach ($fields_of_form as $field) {
                $field_name = $field['name'];
                $filter_field = array_filter($fields, function ($v) use ($field_name) {
                    return $v['name'] == $field_name;
                }, ARRAY_FILTER_USE_BOTH);
                $item = count($filter_field) > 0 ? [...$filter_field][0] : [];
                foreach (['options', 'default_value', 'class', 'rules'] as $key) {
                    if (array_key_exists($key, $field)) {
                        $item[$key] = $field[$key];
                    }
                }
                $item_data = [];
                foreach (['options', 'rules', 'step', 'panel', 'position'] as $key) {
                    if (array_key_exists($key, $field)) {
                        $item_data[$key] = $field[$key];
                    }
                }
                $fields_data[] = ['field' => $item, 'data' => $item_data];
            }
            $forms_data[] = ["form" => $form_item, "fields" => $fields_data];

        }
        return $forms_data;


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

    if (!function_exists('unique_check')) {
        function unique_check($table, $column, $value): bool
        {
            return DB::table($table)->where($column, $value)->exists();
        }
    }
}

