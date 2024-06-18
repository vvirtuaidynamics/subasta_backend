<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'sa' => 'boolean',
        'active' => 'boolean'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_has_roles', 'user_id', 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_has_permissions', 'user_id', 'permission_id');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function getPermissionByModule($module = null)
    {
        $permissions = Permission::join('user_has_permissions', function ($join) {
            $join->on('permissions.id', '=', 'user_has_permissions.permission_id')
                ->where('user_has_permissions.user_id', '=', $this->id);
        });
        if ($module != null) {
            $permissions =
                $permissions->where('permissions.module_id', '=', $module->id);
        }
        return $permissions;
    }

    public function permissionsByModule($module)
    {
        if ($this->sa) {
            return $module->permissions()->get();
        }
        return $this->getAllPermissions()->where('module_id', $module->id);
    }

    public function getAllPermissions()
    {
        $direct_permissions = $this->permissions();
        $permissions_roles = $this->roles()->whereHas('permissions')->select('permissions.*');
        $permissions = $direct_permissions->union($permissions_roles)->distinct()->get();
        return $permissions;
    }

    public function appList()
    {
        $apps = Application::orderBy('order', 'asc')->get();
        $ids_apps = collect([]);
        foreach ($apps as $app) {
            $modules = $app->modules()->orderBy('order', 'asc')->get();
            $ids_model = collect([]);
            foreach ($modules as $mod) {
                $perms = $this->permissionsByModule($mod);
                $mod->permissions = $perms;
                if ($perms->count() > 0) {
                    $ids_model->push($mod->id);
                    $ids_apps->push($mod->aplicacion_id);
                }
            }
            $app->modules = $modules->whereIn('id', $ids_model);
        }
        $ids_apps = $ids_apps->unique();
        $app_list = collect([]);
        foreach ($ids_apps as $id) {
            foreach ($apps as $app) {
                if ($id == $app->id) {
                    $app_list->push($app);
                }
            }
        }
        return $app_list;
    }

    public function hasAccess($perm)
    {
        $perm = strtolower($perm);
        $permission = Permission::where('code', '=', $perm)->first();
        if ($permission == null) {
            return false;
        }
        if ($this->sa) {
            return true;
        }
        foreach ($this->getAllPermissions() as $p) {
            if ($p->code == $perm) {
                return true;
            }
        }
        return false;
    }
}