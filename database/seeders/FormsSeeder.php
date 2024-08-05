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
            try {
                $module = $moduleRepository->getByColumn($f['form']['module'], 'name');
                $form = $formRepository->create([...$f['form'], "module_id" => $module['id']]);
                $this->command->info('Form named ' . $form->name . ' has ' . count($f['fields']) . ' fields defined');
                foreach ($f['fields'] as $attribute) {
                    if ($attribute['field'] && array_key_exists('name', $attribute['field'])) {
                        $field = $fieldRepository->getByColumn($attribute['field']['name'], 'name');
                        if ($field) {

                            $data = ['options' => '{}'];
                            $append = ['created_at' => format_datetime_for_database(now()), 'updated_at' => format_datetime_for_database(now())];
                            $data = [...$data, ...$attribute['data'], ...$append];
                            $formRepository->addField($form->id, $field->id, $data);
                            $this->command->info('Field ' . $field->name . ' related successfully with form ' . $form->name);
                        }
                    } else {
                        $this->command->warn('Field ' . $field->name . ' not found in field definition');

                    }
                }
            } catch (Exception $e) {
                $this->command->error($e->getMessage());
            }
        }


    }
}
