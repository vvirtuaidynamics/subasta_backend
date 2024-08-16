<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FieldForm extends BaseModel
{
    use HasFactory;


    protected $table = 'field_form';
    protected $guarded = [];

    protected $fillable = ['name', 'step', 'panel', 'position', 'options', 'default_value', 'route'];

    protected $with = ['form', 'field'];

    // RELACIONES
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
