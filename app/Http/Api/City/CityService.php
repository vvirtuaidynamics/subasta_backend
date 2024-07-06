<?php

namespace App\Http\Api\City;

use App\Http\Api\Base\BaseService;
use App\Models\City;


class CityService extends BaseService
{
    public function model(): string
    {
        return City::class;
    }

    public function repository(): string
    {
        return CityRepository::class;
    }

    public function rules(): array
    {
        return [

        ];
    }


}
