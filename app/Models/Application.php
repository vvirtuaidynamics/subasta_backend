<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modelo;


class Application extends Model
{

    public $timestamps = false;

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}