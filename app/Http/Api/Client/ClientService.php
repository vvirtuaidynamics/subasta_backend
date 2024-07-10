<?php

namespace App\Http\Api\Client;

use App\Http\Api\Base\BaseService;
use App\Models\Client;

/**
 * Class UserRepository.
 */
class ClientService extends BaseService
{
    public function model(): string
    {
        return Client::class;
    }

    public function repository(): string
    {
        return ClientRepository::class;
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
            "gender" => "required|in:unknown,male,female",
        ];
    }


}
