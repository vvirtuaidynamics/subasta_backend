<?php

namespace App\Http\Api\City;

use App\Http\Api\Base\BaseRepository;
use App\Models\City;

//use Your Model

/**
 * Class UserRepository.
 */
class CityRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return City::class;
    }
}
