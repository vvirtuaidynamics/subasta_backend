<?php

namespace App\Models;


use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TempFile extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url'
    ];
}
