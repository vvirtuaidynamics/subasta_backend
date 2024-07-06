<?php

namespace App\Http\Api\Bearing;

use App\Http\Api\Base\BaseService;
use App\Models\Bearing;

/**
 * Class UserRepository.
 */
class BearingService extends BaseService
{
    public function model(): string
    {
        return Bearing::class;
    }

    public function repository(): string
    {
        return BearingRepository::class;
    }

    public function rules(): array
    {
        return [
           
        ];
    }


}
