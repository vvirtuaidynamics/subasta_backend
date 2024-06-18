<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aplicacion;

class Module extends Model
{

    public $timestamps = false;

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}