<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'preview' => Str::of($this->body)->substr(0, 100),
            $this->mergeWhen(Route::currentRouteName() === 'articles.show', function () {
                return [
                    'body' => $this->body,
                    'comments' => CommentResource::collection($this->comments),
                ];
            }),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
