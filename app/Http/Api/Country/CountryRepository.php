<?php

namespace App\Http\Api\Country;

use App\Models\Country;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;


/**
 * Class UserRepository.
 */
class CountryRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model() : Country
    {
       return Country::class;
    }
}
