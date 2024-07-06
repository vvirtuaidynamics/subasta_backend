<?php

namespace App\Http\Api\State;

use App\Http\Api\Base\BaseRepository;
use App\Models\State;

//use Your Model

/**
 * Class UserRepository.
 */
class StateRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return State::class;
    }
}
