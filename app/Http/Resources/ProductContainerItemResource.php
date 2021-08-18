<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductContainerItemResource extends JsonResource
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
            'zinc' => ProductItemResource::collection($this->getZincItems()),
            'plastic' => ProductItemResource::collection($this->getPlasticItems()),
        ];
    }
}
