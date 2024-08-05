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

    protected $fillable = ['name', 'position', 'options', 'default_value', 'route', 'class'];

    protected $with = ['fields', 'module'];

    // RELACIONES
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class)->withPivot(['options', 'rules', 'step', 'group', 'order']);
    }

    public function setOptionsAttribute($value)
    {
        if (!isset($value) || $value === '')
            $this->attributes['options'] = "{}";
        else
            $this->attributes['options'] = json_encode($value);
    }

    public function getOptionsAttribute($value)
    {
        if (!isset($value) || $value === '')
            return $value = "{}";
        else
            return json_decode($value);
    }

    public function setDefaultValueAttribute($value)
    {
        if (!isset($value) || $value === '')
            $this->attributes['default_value'] = "{}";
        else
            $this->attributes['default_value'] = json_encode($value);
    }

    public function getDefaultValueAttribute($value)
    {
        if (!isset($value) || $value === '')
            return $value = "{}";
        else
            return json_decode($value);
    }

}
