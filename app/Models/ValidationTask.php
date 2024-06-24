<?php

namespace App\Models;

use App\Models\Scopes\ValidationTaskScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Http\Api\Base\BaseModel;

class ValidationTask extends BaseModel
{
  use HasFactory;
  use ValidationTaskScope;

  protected $table = 'validation_tasks';
  protected $guarded = [];

  // Load module relation by default
  protected $with = ['validationable', 'user'];
  protected $appends = ['module', 'full_name'];

  // Constants
  const PERMIT_FORCE_VALIDATION = false;

  // Relations
  public function validationable(): MorphTo
  {
    return $this->morphTo();
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function getModuleAttribute()
  {
    $value = $this->validationable_type;
    return trans('default' . '.' . str()->of($value)->afterLast('\\Models\\'));
  }

  //Casts
  protected function FullName(): Attribute
  {
    return Attribute::make(
      get: function () {
        $name = $this->user->name;
        $last_name = $this->user->last_name;
        return strtoupper($name . ' ' . $last_name);
      }
    );
  }

  protected function createdAt(): Attribute
  {
    return Attribute::make(
      get: fn($value) => Carbon::parse($value)->locale(app()->getLocale())->format(datetime_format_string())
    );
  }
}
