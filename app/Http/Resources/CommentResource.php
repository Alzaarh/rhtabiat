<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'author_name' => $this->author_name,

            $this->mergeWhen(auth('admin')->check(), function () {
                return [
                    'author_email' => $this->author_email,
                    'status_fa' => $this->status_fa,
                ];
            }),

            'body' => $this->body,

            'score' => $this->score,

            'resource_id' => $this->commentable_id,

            'resource_type' => $this->resource_type,
            'resourceable' => $this->commentable,
            'created_at' => $this->created_at,

            'created_at_fa' => Jalalian::fromCarbon(Carbon::make($this->created_at))->format('Y/m/d'),
            "reply" => $this->reply,
        ];
    }
}
