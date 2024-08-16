<?php

namespace App\Http\Api\Form;

use App\Http\Api\Base\BaseRepository;
use App\Models\Form;
use Exception;
use Illuminate\Database\Eloquent\Model;

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
            throw new Exception($e->getMessage());
        }
        return false;

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
            throw new Exception($e->getMessage());

        }
        return false;

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
            throw new Exception($e->getMessage());

        }
        return false;
    }

    public function create(array $data): Model
    {
        $this->unsetClauses();
        $form = $this->getByColumn($data['name'], 'name');
        if ($form && $form instanceof Form)
            return $form;
        else
            return $this->model->create($data);
    }

}
