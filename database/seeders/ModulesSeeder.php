<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tablename = "modules";
        $modules = get_modules();
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
