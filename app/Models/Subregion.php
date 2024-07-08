<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subregion extends BaseModel
{
    use HasFactory;

    protected $table = 'subregions';

    // Relations
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }


}
