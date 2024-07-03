<?php

namespace App\Http\Api\User;

use App\Http\Api\Base\BaseCrudService;
use App\Http\Api\User\Requests\UserStoreRequest;
use App\Http\Api\User\Requests\UserUpdateRequest;
use App\Models\User;

//use Your Model

/**
 * Class UserRepository.
 */
class UserService extends BaseCrudService
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): User
    {
        return User::class;
    }

    public function repository()
    {
        return UserRepository::class;
    }

    public function resource()
    {
        return UserResource::class;
    }

    public function storeRequest()
    {
        return UserStoreRequest::class;
    }

    public function updateRequest()
    {
        return UserUpdateRequest::class;

    }


}
