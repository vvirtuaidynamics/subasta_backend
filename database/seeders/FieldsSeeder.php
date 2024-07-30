<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldsSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tablename = "fields";
        $fields = config('fields.default');
        DB::table($tablename)->insert([
            ...$fields
        ]);

    }

    /**
     * getModules:
     * @return array de modelos a insertar
     */


}
