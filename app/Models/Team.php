<?php

namespace App\Models;

use Laratrust\Models\Role as LaratrustRole;

class Team extends LaratrustRole
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['pivot'];
}
