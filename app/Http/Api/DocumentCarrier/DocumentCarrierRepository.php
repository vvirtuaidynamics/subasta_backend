<?php

namespace App\Http\Api\DocumentCarrier;

use App\Http\Api\Base\BaseRepository;
use App\Models\DocumentCarrier;

class DocumentCarrierRepository extends BaseRepository
{
    public function model(): string
    {
        return DocumentCarrier::class;
    }


}
