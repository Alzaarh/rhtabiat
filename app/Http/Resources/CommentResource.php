<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        $canShowDetails = Str::of(Route::currentRouteName())->contains([
            'comments.index',
            'comments.update',
        ]);
        return [
            'id' => $this->id,
            'author_name' => $this->author_name,
            $this->mergeWhen($canShowDetails, function () {
                return [
                    'author_email' => $this->author_email,
                    'status' => $this->status,
                ];
            }),
            'body' => $this->body,
            'score' => $this->score,
            'resource_id' => $this->commentable_id,
            'resource_type' => $this->resource_type,
            'created_at' => $this->created_at,
        ];
    }
}
