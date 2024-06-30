<?php

namespace App\Http\Api\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; //authorize all to access login
    }

    public function rules(): array
    {
        return [
            'identity' => 'required|max:100',
            'password' => 'required|max:255',
            'remember' => 'nullable|boolean'
        ];
    }
}
