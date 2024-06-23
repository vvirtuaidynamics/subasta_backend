<?php

namespace App\Http\Api\Country;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CountryCurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'currency'=>$this->currency,
            'currency_name'=>$this->currency_name,
            'currency_symbol'=>$this->currency_symbol,


        ];
    }
}
