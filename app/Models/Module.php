<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aplicacion;

class Module extends Model
{

    public $timestamps = false;

    protected $fillable = ['label', 'ico', 'model_name', 'model_namespace', 'order', 'application_id'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}