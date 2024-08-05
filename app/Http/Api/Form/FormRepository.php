<?php

namespace App\Http\Api\Form;

use App\Http\Api\Base\BaseRepository;
use App\Models\Form;
use Exception;

class FormRepository extends BaseRepository
{
    public function model(): string
    {
        return Form::class;
    }

    public function addField($formId, $fieldId, $data = [])
    {
        try {
            $form = $this->getById($formId) || $this->getByColumn($formId, 'name');
            return $form->fields()->attach($fieldId, $data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage);
        }

    }

    public function removeField($formId, $fieldId)
    {
        try {
            $form = $this->getById($formId);
            return $form->fields()->detach($fieldId);
        } catch (Exception $e) {
            throw new Exception($e->getMessage);
        }
    }

    public function syncFields($formId, $data)
    {
        try {
            $form = $this->getById($formId);
            $form->fields()->sync($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage);
        }
    }

    public function updateField($formId, $fieldId, $data = [])
    {
        try {
            $form = $this->getById($formId);
            $form->fields()->updateExistingPivot($fieldId, $data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage);
        }
    }

}
