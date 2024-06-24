<?php

namespace App\Http\Api\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    public static $readonly = false;
    public static $navigationMain = false;
    public static $navigationParent = '';
    public static $navigationLabel = '';
    public static $navigationIcon = '';
    public static $navigationRoute = '';



}
