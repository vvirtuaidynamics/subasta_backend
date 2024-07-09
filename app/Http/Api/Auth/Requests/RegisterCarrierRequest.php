<?php

namespace App\Http\Api\Auth\Requests;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class RegisterCarrierRequest extends FormRequest
{
    use ApiResponseFormatTrait;


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //authorize all to access login
    }


    public function rules(): array
    {
        return [
            "address" => "required|string",
            "phone" => "required|string",
            "date_of_birth" => "required|date",
            "company_name" => "nullable|string",
            "industry" => "nullable|string",
            "user_id" => "required|numeric",
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
