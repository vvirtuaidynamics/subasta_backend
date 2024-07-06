<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithUuid, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'surname',
        'email',
        'password',
        'active',
        'avatar',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
        'permissions'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['super_admin', 'full_name', 'role_names', 'permission_names'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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
