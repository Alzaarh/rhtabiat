<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    public function toArray($request)
    {
        $single = [
            'short_desc' => $this->short_desc,

            'avg_score' => $this->avg_score,

            'desc' => $this->desc,

            'meta_tags' => $this->meta_tags,

            'comments' => CommentResource::collection($this->whenLoaded('comments')),

            'category' => new ProductCategoryResource($this->whenLoaded('category')),

            'items' => ProductItemResource::collection($this->whenLoaded('items')),

            'package_price' => $this->package_price,
        ];

        $single['items'] = !$this->hasContainer() ? $this->items : [
            'zinc' => $this->getZincItems()->all(),
            'plastic' => $this->getPlasticItems()->all(),
        ];

        return array_merge((new IndexProduct($this))->toArray($request), $single);
    }
}
