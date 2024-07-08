<?php

namespace App\Http\Api\Carrier;

use App\Http\Api\Base\BaseService;
use App\Models\Carrier;

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
            'surname' => 'nullable|string|max:50',
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
            "gender" => "required|in:'unknown,male,female'",
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


}
