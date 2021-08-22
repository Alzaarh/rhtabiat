<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => new ImageCollectionResource($this->image),
            'preview' => $this->short_desc,
            'meta_tags' => json_decode($this->meta),
            'is_verified' => $this->is_verified,
            'is_waiting' => $this->is_waiting,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'created_at_fa' => $this->created_at_fa,
            'updated_at_fa' => $this->updated_at_fa,
        ];
    }
}
