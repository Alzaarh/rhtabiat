<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'alt' => $this->alt,
            'url' => $this->is_server_serve ? url('storage/' . $this->url) : config('app.webapp_domain') . $this->url,
            'group' => $this->group,
            'short_desc' => $this->short_desc,
            'desc' => $this->desc,
//            'height' => $this->height,
//            'width' => $this->width,
//            'size' => $this->size,
            'created_at_fa' => $this->created_at_fa,
            'updated_at_fa' => $this->updated_at_fa,
        ];
    }
}
