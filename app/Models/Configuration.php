<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Configuration extends BaseModel
{
    use HasFactory;

    protected $fillable = ['configuration'];

    protected $with = ['configurationable'];

    public function configurationable(): MorphTo
    {
        return $this->morphTo();

    }
}
