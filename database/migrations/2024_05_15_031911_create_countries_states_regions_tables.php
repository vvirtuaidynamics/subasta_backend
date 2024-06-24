<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        Correr solo en producción si hace falta.
//        $table = 'regions';
//
//        Schema::create($table, function (Blueprint $table) {
//            $table->unsignedBigInteger('id')->primary(true);
//            $table->string('name');
//            $table->string('wikiDataId');
//            $table->json('translations');
//        });
//
//        // Seed region table
//        DB::table($table)->delete();
//
//        $json = file_get_contents('database/data/regions.json');
//        $data = json_decode($json, true);
//        if($data)
//            foreach ($data as $region){
//                DB::table($table)->insert([
//                    'id'=>$region['id'],
//                    'name'=>$region['name'],
//                    'translations'=>json_encode($region['translations']),
//                    'wikiDataId'=>$region['wikiDataId'],
//                ]);
//
//            }
//
//        $table = 'subregions';
//
//        Schema::create($table, function (Blueprint $table) {
//            $table->unsignedBigInteger('id')->primary(true);
//            $table->unsignedBigInteger('region_id');
//            $table->string('name');
//            $table->json('translations');
//            $table->string('wikiDataId');
//        });
//        DB::table($table)->delete();
//
//        $json = file_get_contents('database/data/subregions.json');
//        $data = json_decode($json, true);
//        if($data)
//            foreach ($data as $item){
//                DB::table($table)->insert([
//                    'id'=>$item['id'],
//                    'region_id'=>$item['region_id'],
//                    'name'=>$item['name'],
//                    'wikiDataId'=>$item['wikiDataId'],
//                    'translations'=>json_encode($item['translations']),
//                ]);
//
//            }

        $table = 'countries';

        Schema::create($table, function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(true);
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('subregion_id');
            $table->string('name');
            $table->string('iso3');
            $table->string('iso2');
            $table->string('numeric_code');
            $table->string('phone_code');
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_name')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('tld')->nullable();
            $table->string('native')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->json('timezones')->nullable();
            $table->json('translations')->nullable();
        });

        DB::table($table)->delete();

        $json = file_get_contents('database/data/countries.json');
        $data = json_decode($json, true);
        if($data)
            foreach ($data as $item){
                DB::table($table)->insert([
                    'id'=>$item['id'],
                    'region_id'=>intval($item['region_id']),
                    'subregion_id'=>intval($item['subregion_id']),
                    'name'=>$item['name'],
                    'iso3'=>$item['iso3'],
                    'iso2'=>$item['iso2'],
                    'numeric_code'=>$item['numeric_code'],
                    'phone_code'=>$item['phone_code'],
                    'currency'=>$item['currency'],
                    'currency_name'=>$item['currency_name'],
                    'currency_symbol'=>$item['currency_symbol'],
                    'tld'=>$item['tld'],
                    'capital'=>$item['capital'],
                    'nationality'=>$item['nationality'],
                    'native'=>$item['native'],
                    'region'=>$item['region'],
                    'subregion'=>$item['subregion'],
                    'latitude'=>$item['latitude'],
                    'longitude'=>$item['longitude'],
                    'timezones'=>json_encode($item['timezones']),
                    'translations'=>json_encode($item['translations']),
                ]);

            }

        $table = 'states';

        Schema::create($table, function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(true);
            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->string('country_code');
            $table->string('country_name');
            $table->string('state_code');
            $table->string('type')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });

        DB::table($table)->delete();

        $json = file_get_contents('database/data/states.json');
        $data = json_decode($json, true);
        if($data)
            foreach ($data as $item){
                DB::table($table)->insert([
                    'id'=>$item['id'],
                    'country_id'=>$item['country_id'],
                    'name'=>$item['name'],
                    'country_code'=>$item['country_code'],
                    'country_name'=>$item['country_name'],
                    'state_code'=>$item['state_code'],
                    'type'=>$item['type'],
                    'latitude'=>$item['latitude'],
                    'longitude'=>$item['longitude'],
                ]);

            }

//        Correr solo en producción si es necesario.

        $table = 'cities';

        Schema::create($table, function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(true);
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->string('state_code');
            $table->string('state_name');
            $table->string('country_code');
            $table->string('country_name');
            $table->string('latitude');
            $table->string('longitude');

        });

        $json = file_get_contents('database/data/cities.json');
        $data = json_decode($json, true);
        $country_ids = array(233,207);
        if($data)
            foreach ($data as $item){
               if(in_array($item['country_id'], $country_ids))
               {
                DB::table($table)->insert([
                    'id'=>$item['id'],
                    'name'=>$item['name'],
                    'country_id'=>$item['country_id'],
                    'country_code'=>$item['country_code'],
                    'country_name'=>$item['country_name'],
                    'state_id'=>$item['state_id'],
                    'state_code'=>$item['state_code'],
                    'state_name'=>$item['state_name'],
                    'latitude'=>$item['latitude'],
                    'longitude'=>$item['longitude'],
                 ]);
                }
            }



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
        Schema::dropIfExists('subregions');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('states');
        Schema::dropIfExists('cities');
    }
};
