<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $promoCodeOff = 0;
        if ($this->getPromoCode()) {
            $promoCodeOff = $this->getPromoCode()->calculateOff($this->getPriceWithoutOff());
        }
        return [
            'id' => $this->id,
            'code' => $this->code,
            'price' => $this->getPrice(),
            'promoCode' => $this->when($this->getPromoCode(), $this->getPromoCode()),
            'promoCodeOff' => $this->when($promoCodeOff, $promoCodeOff),
            'status' => $this->translateStatus(),
            'products' => ProductItemResource::collection($this->whenLoaded('items')),
            'purchasedByGuest' => new GuestResource($this->whenLoaded('purchasedByGuest')),
            "purchasedByUser" => new AddressResource($this->whenLoaded("address")),
            'delivery_cost' => $this->delivery_cost,
            'package_price' => $this->package_price,
            'deliveryCode' => $this->getDeliveryCode(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
