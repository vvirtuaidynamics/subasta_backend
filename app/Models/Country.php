<?php

namespace App\Models;

use App\Http\Api\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Country extends BaseModel
{
    use HasFactory;
    protected $table = 'countries';



    // Relations
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function subregion(): BelongsTo
    {
        return $this->belongsTo(Subregion::class);
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['flag'];

    /**
     * Get the flag_url for the country.
     *
     * @return string
     */
    public function getFlagAttribute()
    {
        return asset("/images/flags/$this->iso2.svg");
    }



}
