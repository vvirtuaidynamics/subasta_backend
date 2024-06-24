<?php

namespace App\Http\Api\Country\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'iso3'=>$this->iso3,
            'iso2'=>$this->iso2,
            'phone_code'=>$this->phone_code,
            'numeric_code'=>$this->numeric_code,
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'timezones'=>$this->timezones,
            'translations'=>$this->translations,
            'flag'=>$this->flag,
        ];
    }
}
