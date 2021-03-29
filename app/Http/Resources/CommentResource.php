<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'authorName' => $this->author_name,
            $this->mergeWhen($request->has('withDetails'), [
                'email' => $this->email,
                'status' => $this->status,
                'belongsTo' => [
                    'type' => $this->commentable_type === 'App\Models\Article' ?
                    'Article' : 'Product', 
                    'data' => $this->commentable,
                ],
            ]),
            'body' => $this->body,
            'score' => $this->score,
            'created_at' => $this->created_at,
        ];
    }
}
