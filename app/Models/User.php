<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithUuid, LogsActivity, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty();
    }

    protected $fillable = [
        'username',
        'name',
        'surname',
        'email',
        'password',
        'configuration',
        'active',
        'avatar',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'roles',
        'permissions'
    ];

    protected $appends = ['super_admin', 'full_name', 'role_names', 'permission_names'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = app('hash')->make($value);
    }

    public function setUuidAttribute()
    {
        $this->attributes['uuid'] = \Illuminate\Support\Str::uuid();
    }

    public function setAvatarAttribute($value)
    {
        if (is_file($value)) {
            $filename = time() . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('avatars'), $filename);
            $this->attributes['avatar'] = $filename;
        } else {
            $this->attributes['avatar'] = $value;
        }
    }

    public function setConfigurationAttribute($value)
    {
        if (!isset($value) || $value === null || $value === '') $this->attributes['configuration'] = "{}";
        $this->attributes['configuration'] = json_encode($value);
    }

    public function getConfigurationAttribute($value)
    {
        if (!isset($value) || $value === null || $value === '') $value = "{}";
        return json_decode($value);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return format_datetime_for_display($value);
    }

    public function getLastLoginAtAttribute($value)
    {
        return format_datetime_for_display($value);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getSuperAdminAttribute()
    {
        return $this->hasRole(config('permission.super_admin_role_name'));
    }

    public function getPermissionNamesAttribute()
    {
        return collect($this->getAllPermissions())->pluck('name')->toArray();
    }

    public function getRoleNamesAttribute()
    {
        return collect($this->roles)->pluck('name')->toArray();
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value) {
                    return asset('storage/' . $value);
                }
                return asset('storage/avatars/default.png');
            }
        );
    }
}
