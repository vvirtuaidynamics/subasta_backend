<?php

namespace App\Http\Api\Carrier;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Http\Api\Base\BaseService;
use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * Class UserRepository.
 */
class CarrierService extends BaseService
{
    public function model(): string
    {
        return Carrier::class;
    }

    public function repository(): string
    {
        return CarrierRepository::class;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // 'have to receive password_confirmation'
            'active' => 'boolean|nullable',
            'avatar' => 'image|nullable|mimes:jpeg,png,jpg,gif|max:2048',

            "address" => "required|string",
            "phone" => "required|string",
            "date_of_birth" => "required|date",
            "company_name" => "nullable|string",
            "industry" => "nullable|string",
            "user_id" => "nullable|numeric",
            "about_me" => "nullable|string",
            "photo" => "nullable|string",
            "gender" => "required|in:unknown,male,female",
            "transportation_card" => "nullable|file|max:10240",
            "merchandise_insurance" => "nullable|file|max:10240",
            "high_social_security" => "nullable|file|max:10240",
            "payment_current" => "nullable|file|max:10240",
            "vehicle_insurance" => "nullable|file|max:10240",
            "itv" => "nullable|file|max:10240",
            "end_date_transportation_card" => "nullable|date",
            "end_date_merchandise_insurance" => "nullable|date",
            "end_date_high_social_security" => "nullable|date",
            "end_date_payment_current" => "nullable|date",
            "end_date_vehicle_insurance" => "nullable|date",
            "end_date_itv" => "nullable|date",
        ];
    }

    public function create(Request $request, $getModel = false)
    {
        // Authorization check
        $user = auth()->user();
        $require_permission = strtolower($this->getBaseModel()) . ':create';
        if (!$user || (!$user->super_admin || !in_array($require_permission, $user->permission_names)))
            $this->sendError(ApiResponseMessages::FORBIDDEN, ApiResponseCodes::HTTP_FORBIDDEN);
        try {
            $request = $this->mergeRequestBefore($request); // Add request default parameters before insert
            $validator = $this->makeValidator($request->all());
            $validatedData = $validator->validate();
            $result = $this->repository->create($validatedData);

            if ($getModel) return $result;

            return $this->sendResponse($result, ApiResponseMessages::CREATED_SUCCESSFULLY);
        } catch (ValidationException $e) {
            return $this->sendError(
                ApiResponseMessages::UNPROCESSABLE_CONTENT,
                ApiResponseCodes::HTTP_UNPROCESSABLE_CONTENT,
                ['errors' => $e->errors()]
            );
        }

    }


}
