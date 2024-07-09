<?php

namespace App\Http\Api\Auth\Requests;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class RegisterClientRequest extends FormRequest
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
            "user_id" => "nullable|numeric",
            "about_me" => "nullable|string",
            "photo" => "nullable|string",
            "gender" => "required|in:'unknown,male,female'",
        ];
    }

}
