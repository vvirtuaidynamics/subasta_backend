<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends BaseModel
{
    use HasFactory;

    protected $table = "modules";

    protected $fillable = [
        'name',
        'label',
        'url',
        'ico',
        'model_name',
        'model_namespace',
        'readonly',
        'is_main',
        'order',
        'parent_id'
    ];

    protected $appends = [];

    public function children(): HasMany
    {
        return $this->hasMany(Module::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }


}
