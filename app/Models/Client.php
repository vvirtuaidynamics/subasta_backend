<?php

namespace App\Models;

use App\Enums\ValidationStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Http\Api\Base\BaseModel;


class Client extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $with = ['user'];

    protected $fillable = [
        'address', 'phone', 'date_of_birth', 'gender', 'company_name', 'industry', 'user_id', 'about_me', 'photo'
    ];

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

    public function getStatusAttribute()
    {
        return $this->user()->get('active');
    }

    public function getFullNameAttribute()
    {
        return $this->user()->get('name') . ' ' . $this->user()->get('name');
    }
}
