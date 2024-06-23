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
     * @return Country
     */
    public function model()
    {
       return Country::class;
    }

}
