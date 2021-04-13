<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'weight' => $this->weight,
            'container' => $this->getContainerFarsi(),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'cart_quantity' => $this->whenPivotLoaded(
                'cart_product_item',
                fn () => $this->pivot->quantity
            ),
            'product' => $this->whenLoaded(
                'product',
                new IndexProductResource($this->product)
            ),
        ];
    }
}
