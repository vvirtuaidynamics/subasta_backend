<?php

namespace App\Http\Api\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rows' => $this->items(),
            'pagination' => [
                'total' => $this->total(),
                'currentPage' => $this->currentPage(),
                'perPage' => $this->perPage(),
                'lastPage' => $this->lastPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
        ];
    }
}
