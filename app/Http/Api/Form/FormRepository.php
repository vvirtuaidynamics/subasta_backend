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

    /**
     * @throws Exception
     */
    public function addField($formId, $fieldId, $data = [])
    {
        try {
            $form = $this->getById($formId);
            if (!$form) $form = $this->getByColumn($formId, 'name');
            if ($form instanceof \App\Models\Form) {
                $form->fields()->attach([$fieldId => $data]);
            }
            return true;

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

    }

    public function removeField($formId, $fieldId)
    {
        try {
            $form = $this->getById($formId);
            if (!$form) $form = $this->getByColumn($formId, 'name');
            if ($form instanceof \App\Models\Form) {
                return $form->fields()->detach($fieldId);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateField($formId, $fieldId, $data = [])
    {
        try {
            $form = $this->getById($formId);
            if (!$form) $form = $this->getByColumn($formId, 'name');
            if ($form instanceof \App\Models\Form) {
                $form->fields()->updateExistingPivot($fieldId, $data);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
