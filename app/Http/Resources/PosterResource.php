<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'image' => $this->image,
        ];
    }
}
