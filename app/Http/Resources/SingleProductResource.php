<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $single = [
            'short_desc' => $this->short_desc,
            'avg_score' => $this->avg_score,
            'desc' => $this->desc,
            'meta_tags' => $this->meta_tags,

            'comments' => CommentResource::collection($this->comments),
        ];

        $single['items'] = !$this->hasContainer()
            ? $this->items
            : [
                'zink' => $this->getZinkItems(),
                'plastic' => $this->getPlasticItems()
            ];

        return array_merge(
            (new IndexProductResource($this))->toArray($request),
            $single
        );
    }
}
