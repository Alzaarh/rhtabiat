<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class IndexProduct extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'slug' => $this->slug,

            'image' => new ImageCollectionResource($this->image),

            'price' => $this->when(filled($this->price), $this->price),

            'min_price' => $this->when(filled($this->min_price), $this->min_price),

            'max_price' => $this->when(filled($this->max_price), $this->max_price),

            'off' => $this->off,
            'is_best_selling' => $this->is_best_selling,
        ];
    }
}
