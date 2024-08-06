<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Http\Api\Form\FormRepository;
use App\Http\Api\Field\FieldRepository;
use App\Http\Api\Module\ModuleRepository;
use App\Models\Form;
use App\Models\Field;
use App\Models\FieldForm;

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
            $this->command->info('Processing form: ' . $f['form']['name']);
            $module = $moduleRepository->getByColumn($f['form']['module'], 'name');
            $form = $formRepository->create([...$f['form'], "module_id" => $module->id]);
            $this->command->info('Created form: ' . $form->name . ' with ' . count($f['fields']) . ' fields');
            foreach ($f['fields'] as $attribute) {
                if ($attribute['field'] && array_key_exists('name', $attribute['field'])) {
//                    $field = Field::all()->firstWhere('name', '=', $attribute['field']['name']);
                    $field = $fieldRepository->getByColumn($attribute['field']['name'], 'name');
                    $this->command->info($f['form']['name'] . ' -> ' . $attribute['field']['name']);
                    if ($field != null && $form != null) {
                        $data = ['options' => '{}'];
                        $append = ['created_at' => format_datetime_for_database(now()), 'updated_at' => format_datetime_for_database(now())];
                        $data = [...$data, ...$attribute['data'], ...$append];

                        $form->fields()->attach($field->id, [...$data]);
                        $this->command->info('Attached field: ' . $field->name . ' to form: ' . $form->name);
//                        $formRepository->addField($form->id, $field->id, $data);
                    } else {
                        $this->command->warn('Field ' . $field->name || 'null' . ' not found in field definition');

                    }
//
                } else {
                    $this->command->warn('Field ' . $attribute['field']['name'] . ' not found in field definition');
                    var_dump($attribute);

                }
            }

        }

    }
}
