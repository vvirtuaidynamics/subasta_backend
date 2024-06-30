<?php

namespace App\Http\Api\Auth\Requests;

use App\Enums\ApiResponseCodes;
use App\Enums\ApiResponseMessages;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    use ApiResponseFormatTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //authorize all to access login
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3|unique:users,username',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:5',
            'active' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'active' => false,
        ]);
    }

}
