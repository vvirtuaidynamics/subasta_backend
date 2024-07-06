<?php

namespace App\Http\Api\Client;

use App\Http\Api\Base\BaseRepository;
use App\Models\Client;

//use Your Model

/**
 * Class UserRepository.
 */
class ClientRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model(): string
    {
        return Client::class;
    }
}
