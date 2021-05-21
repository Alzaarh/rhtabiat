<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'price' => $this->price,
            'discount_code' => $this->discount_code,
            'status_fa' => Order::STATUS_FA[$this->status],
            'products' => ProductItemResource::collection($this->whenLoaded('items')),
            'user' => $this->user,
            'created_at' => $this->created_at,
            'created_at_fa' => $this->created_at_fa,
            'updated_at' => $this->updated_at,
        ];
    }
}
