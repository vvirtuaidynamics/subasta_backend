<?php

namespace App\Http\Api\User;

use App\Http\Api\Base\BaseService;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class UserRepository.
 */
class UserService extends BaseService
{
    public function model(): string
    {
        return User::class;
    }

    public function repository(): string
    {
        return UserRepository::class;
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
            'avatar' => 'image|nullable|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function mergeRequestBefore(Request $request): Request
    {
        if (!$request->has('active'))
            $request = $request->merge(['active' => 0]);
        return $request;
    }

}
