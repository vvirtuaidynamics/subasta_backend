<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
{
    public function getModules(): array
    {
        //TODO Para adicionar modulos readonly que son nomencladores o modulos que el usuario no tiene
        // que editar.
        $nomencladores = ['country', 'state', 'city', 'activity'];
        //TODO Para cambiar o adicionar modulos modificar el array $modulos.
        // Tener en cuenta que los modulos con parent en null significa que es un modulo principal.
        // Name siempre hara referencia al modelo en minuscula.
        $modulos = [

            ['name' => 'administration', 'label' => 'Administration', 'url' => '', 'icon' => 'mdi-account-cog-outline', 'order' => 1, 'parent' => ''],
            ['name' => 'role', 'label' => 'Roles', 'url' => 'role', 'icon' => 'mdi-account-multiple-outline', 'order' => 1, 'parent' => 'administration'],
            ['name' => 'user', 'label' => 'Users', 'url' => 'user', 'icon' => 'mdi-account-outline', 'order' => 2, 'parent' => 'administration'],
            ['name' => 'activity', 'label' => 'Activity', 'url' => 'activity', 'icon' => 'mdi-account-clock-outline', 'order' => 3, 'parent' => 'administration'],

            ['name' => 'persons', 'label' => 'Persons', 'url' => '', 'icon' => 'mdi-account-multiple-outline', 'order' => 2, 'parent' => ''],
            ['name' => 'carrier', 'label' => 'Carrier', 'url' => 'carrier', 'icon' => 'mdi-human-male', 'order' => 1, 'parent' => 'persons'],
            ['name' => 'client', 'label' => 'Client', 'url' => 'client', 'icon' => 'mdi-briefcase-variant-outline', 'order' => 2, 'parent' => 'persons'],

            ['name' => 'bearing', 'label' => 'Bearing', 'url' => 'bearing', 'icon' => 'mdi-cube-outline', 'order' => 3, 'parent' => ''],

            ['name' => 'validationtask', 'label' => 'Validation Task', 'url' => 'validation-task', 'icon' => 'mdi-calendar-multiple-check', 'order' => 4, 'parent' => '']
        ];

        $models = get_models(); //get models data and fields.
        logger('models: ', $models);
        $results = array();
        foreach ($modulos as $modulo) {
            $modulo_model = null;
            $m = [
                'name' => $modulo['name'],
                'label' => $modulo['label'],
                'title' => isset($modulo['title']) ? $modulo['title'] : $modulo['label'],
                'icon' => $modulo['icon'],
                'url' => isset($modulo['url']) ? $modulo['url'] : $modulo['name'],
                'order' => $modulo['order'],
                'readonly' => in_array(strtolower($modulo['name']), $nomencladores,),
            ];
            if (isset($modulo['parent'])) {
                $modulo_model = collect(array_filter($models, function ($model) use ($modulo) {
                    return strtolower($model['name']) === strtolower($modulo['name']);
                }))->first();
                if (isset($modulo_model)) {
                    $m['model_name'] = $modulo_model['name'];
                    $m['model_namespace'] = $modulo_model['namespace'];
                    $m['fields'] = json_encode($modulo_model['fields']);
                } else {
                    $m['model_name'] = '';
                    $m['model_namespace'] = '';
                    $m['fields'] = '{}';

                }
                $m['parent_name'] = isset($modulo['parent']) ? $modulo['parent'] : '';
            }
            $results[] = $m;
        }
        return $results;

    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tablename = "modules";
        $modules = $this->getModules();
        DB::table($tablename)->truncate();
        DB::table($tablename)->insert([
            ...$modules
        ]);

    }

    /**
     * getModules:
     * @return array de modelos a insertar
     */


}
