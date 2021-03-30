<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'totalPrice' => $this->total_price,
            'statusFa' => $this->status_fa,
            'createdAt' => $this->created_at,
        ];
    }
}
