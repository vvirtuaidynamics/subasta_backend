<?php

namespace App\Http\Api\Form;

use App\Http\Api\Base\BaseRepository;
use App\Models\Form;

class FormRepository extends BaseRepository
{
    public function model(): string
    {
        return Form::class;
    }

    public function addFormField($fieldId, $data = [])
    {
        $this->model->fields()->attach($fieldId, $data);
    }

    public function removeFormField($fieldId)
    {
        $this->model->fields()->detach($fieldId);
    }

    public function syncFormField($data)
    {
        $this->model->fields()->sync($data);
    }

    public function updateFormField($fieldId, $data = [])
    {
        $this->model->fields()->updateExistingPivot($fieldId, $data);
    }

}
