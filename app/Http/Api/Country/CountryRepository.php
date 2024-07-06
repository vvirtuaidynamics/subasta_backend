<?php

namespace App\Http\Api\Country;

use App\Models\Country;
use App\Http\Api\Base\BaseRepository;


/**
 * Class UserRepository.
 */
class CountryRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Country::class;
    }

}
