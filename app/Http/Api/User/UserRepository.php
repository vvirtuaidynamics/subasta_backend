<?php

namespace App\Http\Api\User;

use App\Http\Api\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;
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
    public function model(): string
    {
        return User::class;
    }
}
