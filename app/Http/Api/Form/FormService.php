<?php

namespace App\Http\Api\Form;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Http\Api\Base\BaseService;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

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
            'username' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:50',
            'surname' => 'nullable|string|max:50',
            'email' => 'required|string|max:255|unique:users',
            'options' => 'json|nullable',
            'class' => 'string|nullable',

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
}