<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';
    protected $fillable = ['name', 'label', 'placeholder', 'component', 'type', 'include', 'options', 'default_value'];

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class)->withPivot(['options', 'rules', 'step', 'panel', 'position'])->withTimestamps();
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = slugify($value);
    }

    public function setOptionsAttribute($value)
    {
        if (!isset($value) || $value === null || $value === '') $this->attributes['options'] = "{}";
        $this->attributes['options'] = json_encode($value);
    }

    public function getOptionsAttribute($value)
    {
        if (!isset($value) || $value === null || $value === '') $value = "{}";
        return json_decode($value);
    }

    public function setCreatedAtAttribute($value)
    {
        if (!isset($value) || $value === null || $value === '')
            $this->attributes['created_at'] = format_datetime_for_database(now());
    }

    public function setUpdatedAtAttribute($value)
    {
        if (!isset($value) || $value === null || $value === '')
            $this->attributes['updated_at'] = format_datetime_for_database(now());
    }


}
