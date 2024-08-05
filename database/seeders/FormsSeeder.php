<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Http\Api\Form\FormRepository;
use App\Http\Api\Field\FieldRepository;
use App\Http\Api\Module\ModuleRepository;

use function Laravel\Prompts\warning;

class FormsSeeder extends Seeder
{
    public function run(): void
    {
        $formRepository = new FormRepository();
        $fieldRepository = new FieldRepository();
        $moduleRepository = new ModuleRepository();

        // Get data of all form from config files
        $forms_data = get_forms_data();
        // Each form has ['form'=>[name,module,label,position,route,options,default_value,class], 'fields'=>array]

        foreach ($forms_data as $f) {
            var_dump($f);
//            try {
//                $module = $moduleRepository->getByColumn($f['form']['module'], 'name');
//                $fdata = array_filter($f['form'], function ($k) {
//                    return $k != 'module';
//                }, ARRAY_FILTER_USE_KEY);
//                $form = $formRepository->create([...$fdata, "module_id" => $module['id']]);
//
//                //dd($f);
//
//                foreach ($f['fields'] as $attr) {
//                    if ($attr['field'] && array_key_exists('name', $attr['field'])) {
//                        $field = $fieldRepository->getByColumn($attr['field']['name'], 'name');
//                        if ($field) {
//
//                            $data = ['options' => '{}'];
//                            $append = ['created_at' => format_datetime_for_database(now()), 'updated_at' => format_datetime_for_database(now())];
//                            $data = [...$data, ...$attr['data'], ...['options' => json_encode(['name' => ['daismel', 'tamayo']])], ...$append];
//                            var_dump(["form_id" => $form->id, "field_id" => $field->id, $data]);
//                            $field_form_item = ["form_id" => $form->id, "field_id" => $field->id, ...$data];
//                            DB::table(config('form.field_form_tablename', 'field_form'))->insert([...$field_form_item]);
//                            //                            $formRepository->addField($form->id, $field->id, $data);
//                        } else {
////                            warning('no field');
////
//                        }
//                    }
//                }
//            } catch (Exception $e) {
//                $this->command->error($e->getMessage());
//            }
        }


    }
}
