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
        // Each form has ['form'=>[name,module,label,position,route,options,default_value], 'fields'=>array]
        foreach ($forms_data as $f) {
            $module = $moduleRepository->getByColumn($f['form']['model'], 'name');
            $append_form = $module && $module->id ? ["module_id" => $module->id] : [];
            $form = $formRepository->create([...$f['form'], ...$append_form]);
            if (!empty($form)) {
                foreach ($f['fields'] as $attribute) {
                    $field = null;
                    if ($attribute['field'] && array_key_exists('name', $attribute['field'])) {
                        $field = $fieldRepository->getByColumn($attribute['field']['name'], 'name');
                        if (!empty($field)) {
                            $relatedFields = $form->fields()->pluck('field_id')->toArray();
                            if (!in_array($field->id, $relatedFields)) {
                                $this->command->info('The field ' . $field->name . ' has been attached to form ' . $f['form']['name'] ?? '');
                                $data = ['options' => '{}'];
                                $append = ['created_at' => format_datetime_for_database(now()), 'updated_at' => format_datetime_for_database(now())];
                                $data = [...$data, ...$attribute['data'], ...$append];
                                $form->fields()->attach($field->id, [...$data]);
                            } else {
                                $this->command->info('The field ' . $field->name . ' already present in form ' . $f['form']['name'] ?? '');
                            }
                        }
                    } else {
                        $this->command->info('The field is not valid or not have attribute name');
                    }
                }
            }
        }

    }
}
