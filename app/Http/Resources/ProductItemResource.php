<?php

namespace App\Http\Resources;

use App\Models\ProductItem;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'weight' => $this->weight,
            'container' => $this->container_fa,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'order_quantity' => $this->whenPivotLoaded('order_product_item', fn() => $this->pivot->quantity),
            'cart_quantity' => $this->whenPivotLoaded('cart_product_item', fn() => $this->pivot->quantity),
            'product' => new IndexProduct($this->whenLoaded('product')),
        ];
    }
}
