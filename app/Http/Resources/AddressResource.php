<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'company' => $this->company,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'state' => $this->state,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
        ];
    }
}
