<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestResource extends JsonResource
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
            'orderId' => $this->order_id,
            'name' => $this->name,
            'company' => $this->company,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'province' => new ProvinceResource($this->province),
            'city' => new CityResource($this->city),
            'zipcode' => $this->zipcode,
            'address' => $this->address,
        ];
    }
}
