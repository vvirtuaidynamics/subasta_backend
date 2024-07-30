<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';
    protected $fillable = ['name', 'slug', 'label', 'placeholder', 'component', 'include', 'options', 'default_value'];

    protected $with = ['forms'];

    public function forms()
    {
        return $this->belongsToMany(Form::class)->withPivot(['options', 'rules', 'step', 'group', 'order']);
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
}
