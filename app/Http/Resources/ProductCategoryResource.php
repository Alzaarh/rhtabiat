<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => new ImageCollectionResource($this->image),
//            'image_mobile' => $this->image,
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
