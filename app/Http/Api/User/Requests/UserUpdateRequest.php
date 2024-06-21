<?php

namespace App\Http\Api\User\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name'=> 'string|max:50',
            'surname'=> 'string|max:50',
            'email'=> 'string|max:255',
            'password'=> 'required|string',
            'active'=> 'boolean',
            'avatar'=> 'max:10000|mimes:image/*',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new \HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ]
        ));
    }
}
