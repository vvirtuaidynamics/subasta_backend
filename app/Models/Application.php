<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modelo;


class Application extends Model
{

    public $timestamps = false;

    protected $fillable = ['label', 'ico', 'order'];

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order', 'asc');
    }
}
