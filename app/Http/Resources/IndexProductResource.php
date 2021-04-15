<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => url('storage/' . $this->image),
            'price' => $this->when(filled($this->price), $this->price),
            'min_price' => $this->when(
                filled($this->min_price),
                $this->min_price
            ),
            'max_price' => $this->when(
                filled($this->max_price),
                $this->max_price
            ),
            'off' => $this->off,
        ];
    }
}
