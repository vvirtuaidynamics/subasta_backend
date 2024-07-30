<?php

namespace App\Http\Api\Field;


use App\Http\Api\Base\BaseService;
use App\Models\Field;

class FieldService extends BaseService
{
    public function model(): string
    {
        return Field::class;
    }

    public function repository(): string
    {
        return FieldRepository::class;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:fields',
            'slug' => 'required|string|max:100',
            'label' => 'nullable|string|max:100',
            'placeholder' => 'nullable|string|max:100',
            'component' => 'nullable|string',
            'include' => 'boolean',
            'rules' => 'string|nullable',
            'options' => 'json|nullable',
            'default_value' => 'string|nullable',
        ];
    }


}
