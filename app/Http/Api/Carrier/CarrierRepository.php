<?php

namespace App\Http\Api\Carrier;

use App\Http\Api\Base\BaseRepository;
use App\Models\Carrier;

//use Your Model

/**
 * Class UserRepository.
 */
class CarrierRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return Carrier::class;
    }
}
