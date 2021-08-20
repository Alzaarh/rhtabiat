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
            // 'discount_code' => $this->discountCode,
            'status' => $this->translateStatus(),
            'products' => ProductItemResource::collection($this->whenLoaded('items')),
            'purchasedByGuest' => new GuestResource($this->whenLoaded('purchasedByGuest')),
            'delivery_cost' => $this->delivery_cost,
            'package_price' => $this->package_price,
            'deliveryCode' => $this->getDeliveryCode(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
