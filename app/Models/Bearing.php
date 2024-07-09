<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bearing extends BaseModel
{
    use HasFactory;

    protected $table = 'bearings';
    use SoftDeletes;
}
