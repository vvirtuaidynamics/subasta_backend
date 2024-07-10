<?php

namespace App\Http\Api\File;

use App\Http\Api\Base\BaseRepository;
use App\Models\File;

class FileRepository extends BaseRepository
{
    public function model(): string
    {
        return File::class;
    }


}
