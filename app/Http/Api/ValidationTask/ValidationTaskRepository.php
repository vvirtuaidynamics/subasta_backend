<?php

namespace App\Http\Api\ValidationTask;

use App\Http\Api\Base\BaseRepository;
use App\Models\ValidationTask;

//use Your Model

/**
 * Class UserRepository.
 */
class ValidationTaskRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return ValidationTask::class;
    }
}
