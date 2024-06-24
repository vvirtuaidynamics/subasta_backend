<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    public $timestamps = false;

    protected $fillable = ['code', 'name', 'module_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_permissions', 'permission_id', 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
