<?php

namespace App\Http\Api\ValidationTask;

use App\Enums\ApiStatus;
use App\Enums\ValidationStatus;
use App\Http\Api\Base\BaseService;

use App\Models\ValidationTask;
use Illuminate\Http\Request;

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

    public function mergeUpdateRequestBefore(Request $request): Request
    {
        $user_auth_id = auth()->user()->getAuthIdentifier();
        $request->whenFilled('status', function (string $input) use ($request, $user_auth_id) {
            if ($input == ValidationStatus::VALIDATED->value) {
                $request->merge(['validated_at' => now()]);
                $request->merge(['who_validated' => $user_auth_id]);
            }
        });
        return $request; // TODO: Change the autogenerated stub
    }

}
