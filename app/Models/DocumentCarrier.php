<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentCarrier extends Model
{
  use HasFactory;

  protected $guarded = [];


  public function carrier(): BelongsTo
  {
    return $this->belongsTo(Carrier::class, 'carrier_id');
  }

  //Casts
  protected function expireDate(): Attribute
  {
    return Attribute::make(
      get: fn($value) => Carbon::parse($value)->locale(app()->getLocale())->format(date_format_string())
    );
  }

  protected function validated(): Attribute
  {
    return Attribute::make(
      get: fn($value) => $value ? Carbon::parse($value)->locale(app()->getLocale())->format(datetime_format_string()) : null
    );
  }
}
