<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'receiverName' => $this->receiver_name,
            'address' => $this->address,
            $this->mergeWhen($request->has('withDetails'), function () {
                return [
                    'receiverCompany' => $this->receiverCompany,
                    'mobile' => $this->mobile,
                    'phone' => $this->phone,
                    'state' => $this->state,
                    'city' => $this->city,
                    'zipcode' => $this->zipcode,
                ];
            }),
        ];
    }
}
