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
            'discount_code' => $this->discountCode,
            'status_fa' => Order::STATUS_FA[$this->status],
            'products' => ProductItemResource::collection($this->whenLoaded('items')),
            'user' => $this->user,
            'delivery_cost' => $this->delivery_cost,
            'package_price' => $this->package_price,
            'created_at' => $this->created_at,
            'created_at_fa' => $this->created_at_fa,
            'updated_at' => $this->updated_at,
        ];
    }
}
