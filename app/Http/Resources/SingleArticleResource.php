<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'preview' => $this->short_desc,
            'image' => new ImageCollectionResource($this->image),
            'meta_tags' => json_decode($this->meta),
            'is_verified' => $this->is_verified,
            'is_waiting' => $this->is_waiting,
            'body' => $this->body,
            'category' => $this->category,
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_fa' => $this->created_at_fa,
            'updated_at_fa' => $this->updated_at_fa,
        ];
    }
}
