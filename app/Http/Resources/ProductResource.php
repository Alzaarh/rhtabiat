<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        // two different resources for items with and without containers.
        $items = null;
        if ($this->relationLoaded('items') && $this->hasContainer()) {
            $items = new ProductContainerItemResource($this);
        }
        if ($this->relationLoaded('items') && !$this->hasContainer()) {
            $items = ProductItemResource::collection($this->items);
        }

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'meta_tags' => $this->getMetaTags(),
            'short_desc' => $this->getShortDescription(),
            'desc' => $this->when(Route::currentRouteName('product.show'), $this->getDescription()),
            'price' => $this->getPrice(),
            'off' => $this->getOff(),
            'is_best_selling' => $this->getIsBestSelling(),
            'package_price' => $this->getPackagePrice(),
            'unit' => $this->getUnitTranslation(),
            'image' => new ImageResource($this->whenLoaded('image')),
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'items' => $this->when(isset($items), $items),
            'createdAt' => $this->getCreatedAt(),
        ];
    }
}
