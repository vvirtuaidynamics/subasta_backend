<?php

namespace App\Http\Api\Carrier;

use App\Http\Api\Base\BaseRepository;
use App\Models\Carrier;

class CarrierRepository extends BaseRepository
{
    public function model(): string
    {
        return Carrier::class;
    }
}
