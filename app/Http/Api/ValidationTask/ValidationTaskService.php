<?php

namespace App\Http\Api\ValidationTask;

use App\Http\Api\Base\BaseService;

use App\Models\ValidationTask;

/**
 * Class UserRepository.
 */
class ValidationTaskService extends BaseService
{
    public function model(): string
    {
        return ValidationTask::class;
    }

    public function repository(): string
    {
        return ValidationTaskRepository::class;
    }

    public function rules(): array
    {
        return [
            'validationable_type' => 'required|string',
            'validationable_id' => 'required|numeric',
            'status' => 'nullable|string|max:50',
            'validated_at' => 'nullable|date',
            'who_validated' => 'nullable|numeric', // 'have to receive password_confirmation'
            'notes' => 'nullable|string',
            'validation_data' => 'nullable|string'
        ];
    }


}
