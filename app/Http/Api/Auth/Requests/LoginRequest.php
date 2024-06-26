<?php

namespace App\Http\Api\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
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
            'identity' => 'required|max:100',
            'password' => 'required|max:255',
            'remember' => 'boolean'
        ];
    }
    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'identity.required'      => 'The identity address field is required.',
            'identity.max'      => 'The identity field may not be greater than 100 characters.',
            'password.required'   => 'The password field is required.',
            'password.max'        => 'The password field may not be greater than 30 characters.',
        ];
    }
}
