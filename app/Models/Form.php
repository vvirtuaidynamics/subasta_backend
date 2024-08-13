<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Form extends BaseModel
{
    use HasFactory;

    protected $table = 'forms';

    protected $fillable = ['name', 'model', 'label', 'icon', 'data'];

    protected $appends = ['model_field'];


    public function setDataAttribute($value)
    {
        if (!isset($value) || $value === '')
            $this->attributes['data'] = "{}";
        else
            $this->attributes['options'] = json_encode($value);
    }

    public function getModelFieldAttribute()
    {
        return $this->model ? get_models($this->model) : null;
    }

//    public function getOptionsAttribute($value)
//    {
//        if (!isset($value) || $value === '')
//            return $value = "{}";
//        else
//            return json_decode($value);
//    }
//
//    public function setDefaultValueAttribute($value)
//    {
//        if (!isset($value) || $value === '')
//            $this->attributes['default_value'] = "{}";
//        else
//            $this->attributes['default_value'] = json_encode($value);
//    }
//
//    public function getDefaultValueAttribute($value)
//    {
//        if (!isset($value) || $value === '')
//            return $value = "{}";
//        else
//            return json_decode($value);
//    }

}
