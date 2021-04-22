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

            'container' => (empty($this->container) ? null : $this->container === ProductItem::ZINC_CONTAINER) ? 'روحی' : 'پلاستیکی',

            'price' => $this->price,

            'quantity' => $this->quantity,

            'cart_quantity' => $this->whenPivotLoaded('cart_product_item', fn() => $this->pivot->quantity),

            'product' => new IndexProductResource($this->whenLoaded('product')),
        ];
    }
}
