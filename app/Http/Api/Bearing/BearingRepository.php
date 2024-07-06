<?php

namespace App\Http\Api\Bearing;

use App\Http\Api\Base\BaseRepository;
use App\Models\Bearing;

//use Your Model

/**
 * Class UserRepository.
 */
class BearingRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return Bearing::class;
    }
}
