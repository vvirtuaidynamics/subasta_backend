<?php

namespace App\Http\Api\Client;

use App\Http\Api\Base\BaseService;
use App\Models\Client;

/**
 * Class UserRepository.
 */
class ClientService extends BaseService
{
    public function model(): string
    {
        return Client::class;
    }

    public function repository(): string
    {
        return ClientRepository::class;
    }

    public function rules(): array
    {
        return [
            "name" => "required",
            "email" => "required",
            "password" => "required"
        ];
    }


}
