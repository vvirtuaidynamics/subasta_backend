<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Api\Base\BaseModel;
use Illuminate\Support\Facades\Storage;


class DocumentCarrier extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type', 'path', 'size', 'preview'];
    protected $appends = ['file_url'];

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

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

    public function getFileUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
