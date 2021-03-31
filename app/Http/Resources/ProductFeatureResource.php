<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeatureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'product' => $this->whenLoaded('product', $this->product),
            // 'cartQuantity' => $this->whenPivotLoaded('cart_product')
            $this->mergeWhen(isset($this->container), function () {
                return [
                    'container' => [
                        $this->container => [
                            'id' => $this->id,
                            'weight' => $this->weight,
                        ],
                    ],
                ];
            }),
        ];
    }
}
