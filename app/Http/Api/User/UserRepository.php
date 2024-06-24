<?php

namespace App\Http\Api\User;

use App\Http\Api\Base\BaseRepository;
use App\Models\User;

//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model() : User
    {
       return User::class;
    }
}
