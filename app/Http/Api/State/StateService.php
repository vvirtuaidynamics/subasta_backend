<?php

namespace App\Http\Api\State;

use App\Http\Api\Base\BaseService;
use App\Models\State;


class StateService extends BaseService
{
    public function model(): string
    {
        return State::class;
    }

    public function repository(): string
    {
        return StateRepository::class;
    }

    public function rules(): array
    {
        return [

        ];
    }


}
