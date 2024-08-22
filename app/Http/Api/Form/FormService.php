<?php

namespace App\Http\Api\Form;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Http\Api\Base\BaseService;
use App\Models\Form;
use Illuminate\Http\Request;


class FormService extends BaseService
{
    public function model(): string
    {
        return Form::class;
    }

    public function repository(): string
    {
        return FormRepository::class;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50|unique:forms',
            'label' => 'nullable|string',
            'module_id' => 'nullable|numeric',
            'model' => 'nullable|string|max:50',
            'route' => 'nullable|string',
            'options' => 'nullable|json',
            'default_value' => 'nullable|json',
        ];
    }

    public function getFormByName($name, $request)
    {
        if ($name) {
            $f = $this->repository->getByColumn($name, 'name');
            if ($f) {
                return $this->sendResponse($f, ApiResponseMessages::FETCHED_SUCCESSFULLY);
            }
        }
        return $this->sendError(ApiResponseMessages::RESOURCE_NOT_FOUND, ApiResponseCodes::HTTP_NOT_FOUND);
    }

    public function addField($form_id, $field_id, Request $request)
    {
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':update';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            return $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
        $data = $request->has('data') ? $request->input('data') : [];
        return $this->repository->addField($form_id, $field_id, $data);
    }

    public function updateField($form_id, $field_id, Request $request)
    {
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':update';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            return $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
        $data = $request->has('data') ? $request->input('data') : [];
        return $this->repository->updateField($form_id, $field_id, $data);
    }

    public function removeField($form_id, $field_id, Request $request)
    {
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':update';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            return $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
        $data = $request->has('data') ? $request->input('data') : [];
        return $this->repository->removeField($form_id, $field_id, $data);
    }

}
