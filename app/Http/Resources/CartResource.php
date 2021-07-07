<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'items' => ProductFeatureResource::collection($this->products),
            'totalPrice' => $this->total_price,
        ];
    }
}
