<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'image' => new ImageResource($this->whenLoaded('image')),
            'image_mobile' => new ImageResource($this->whenLoaded('imageMobile')),
            'parent' => new self($this->whenLoaded('parent')),
            'parent_id' => $this->getParentId(),
            'children' => self::collection($this->whenLoaded('children')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'createdAt' => $this->getCreatedAt(),
        ];
    }
}
