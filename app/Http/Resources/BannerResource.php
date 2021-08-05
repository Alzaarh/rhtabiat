<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => new ImageCollectionResource($this->image),
            'location' => $this->location,
            'link' => $this->link,
        ];
    }
}
