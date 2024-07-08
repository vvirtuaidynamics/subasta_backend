<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends BaseModel
{
    use HasFactory;

    protected $table = 'states';

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'state_id');
    }

}
