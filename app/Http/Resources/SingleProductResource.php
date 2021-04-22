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
        ];

        $single['items'] = !$this->hasContainer()
            ? $this->items
            : [
                'zinc' => $this->getZincItems(),
                'plastic' => $this->getPlasticItems()
            ];

        return array_merge(
            (new IndexProductResource($this))->toArray($request),
            $single
        );
    }
}
