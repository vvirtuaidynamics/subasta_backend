<?php

namespace App\Http\Api\Module;

use App\Http\Api\Base\BaseRepository;
use App\Models\Module;

class ModuleRepository extends BaseRepository
{
    public function model(): string
    {
        return Module::class;
    }

}
