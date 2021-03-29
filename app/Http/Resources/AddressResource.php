<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'receiver' => $this->receiver,
            'company' => $this->company,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'state' => $this->state,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'address' => $this->address,
        ];
    }
}
