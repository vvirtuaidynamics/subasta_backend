<?php

namespace App\Models;

use App\Enums\ValidationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Carrier extends Model
{
  use HasFactory;

  const SINGLE_RELATION = [
    'user' => [
      'name', 'last_name', 'email'
    ]
  ];

  protected $guarded = [];

  protected $with = ['user', 'documents'];

  protected $fillable = [
    'address', 'phone', 'date_of_birth', 'gender', 'company_name', 'industry', 'user_id', 'about_me', 'photo', 'cover_profile',
    'transportation_card', 'merchandise_insurance', 'high_social_security', 'payment_current', 'vehicle_insurance', 'itv'
  ];

  //Relations
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function validations(): MorphMany
  {
    return $this->morphMany(ValidationTask::class, 'validationable');
  }

  public function lastValidation(): MorphOne
  {
    return $this->morphOne(ValidationTask::class, 'validationable')->latestOfMany();
  }

  public function pendingValidation(): MorphOne
  {
    return $this->morphOne(ValidationTask::class, 'validationable')->where('status', ValidationStatus::PENDING->value);
  }

  public function documents(): HasMany
  {
    return $this->hasMany(DocumentCarrier::class);
  }


  public function scopeFilters($query, $columns, $search = null): void
  {
    if (isset($search)) {
      $query->where(function ($query) use ($search, $columns) {
        foreach (collect($columns)->filter(function ($value) {
          return $value != 'action';
        })->toArray() as $value) {
          $query->orWhere($value, 'like', '%' . $search . '%');
        }
      });
    }
  }

  public function getStatusAttribute()
  {
    return $this->user()->status;
  }

  public function getFullNameAttribute()
  {
    return $this->user()->name . ' ' . $this->user()->last_name;
  }
}
