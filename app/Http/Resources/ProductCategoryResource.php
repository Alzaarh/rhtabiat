<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            'image' => url("storage/{$this->image}"),
            'image_mobile' => url("storage/{$this->image_mobile}"),
            'parent' => new self($this->whenLoaded('parent')),
            'parent_id' => $this->parent_id,
            'children' => self::collection($this->whenLoaded('children')),
            'products' => IndexProduct::collection(
                $this->whenLoaded('products')
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
