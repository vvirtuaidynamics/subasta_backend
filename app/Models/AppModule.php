<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppModule extends Model
{
    use HasFactory;

    protected $table = "app_modules";

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
        return $this->hasMany(AppModule::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AppModule::class, 'parent_id');
    }


}
