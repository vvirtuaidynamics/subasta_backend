<?php

namespace App\Http\Controllers;

use App\Http\Api\Field\FieldRepository;
use App\Http\Api\Form\FormRepository;
use App\Http\Api\Module\ModuleRepository;
use App\Models\Field;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function setFormField()
    {
        $formRepository = new FormRepository();
        $fieldRepository = new FieldRepository();
        $moduleRepository = new ModuleRepository();

        // Get data of all form from config files
        $forms_data = get_forms_data();
        // Each form has ['form'=>[name,module,label,position,route,options,default_value], 'fields'=>array]
        foreach ($forms_data as $f) {
            $module = $moduleRepository->getByColumn($f['form']['module'], 'name');
            $form = $formRepository->create([...$f['form'], "module_id" => $module->id]);
            if (!empty($form)) {
                foreach ($f['fields'] as $attribute) {
                    $field = null;
                    if ($attribute['field'] && array_key_exists('name', $attribute['field'])) {
                        $field = $fieldRepository->getByColumn($attribute['field']['name'], 'name');
                        if (!empty($field)) {
                            $relatedFields = $form->fields()->pluck('field_id')->toArray();
                            if (!in_array($field->id, $relatedFields)) {
                                dump('The field ' . $field->name . ' se ha asociado al form ' . $f['form']['name'] ?? '');

                                $data = ['options' => '{}'];
                                $append = ['created_at' => format_datetime_for_database(now()), 'updated_at' => format_datetime_for_database(now())];
                                $data = [...$data, ...$attribute['data'], ...$append];
                                $form->fields()->attach($field->id, [...$data]);
                            } else {
                                dump('The field ' . $field->name . ' ya se encuentra asociado al form ' . $f['form']['name'] ?? '');
                            }
                        }
                    } else {
                        dump($attribute);

                    }
                }
            }
        }
    }
}
