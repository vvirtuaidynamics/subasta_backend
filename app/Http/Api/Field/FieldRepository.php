<?php

namespace App\Http\Api\Field;

use App\Http\Api\Base\BaseRepository;
use App\Models\Field;

class FieldRepository extends BaseRepository
{
    public function model(): string
    {
        return Field::class;
    }

    public function getFieldByName(string $name)
    {
        return $this->getByColumn($name, 'name');
    }

}
