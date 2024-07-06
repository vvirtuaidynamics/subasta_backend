<?php

namespace App\Http\Api\Country;

use App\Http\Api\Base\BaseCollection;
use App\Http\Api\Base\BaseService;
use App\Http\Api\Country\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryService extends BaseService
{
    public function model(): string
    {
        return Country::class;
    }

    public function repository(): string
    {
        return CountryRepository::class;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
