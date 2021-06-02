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
            'url' => url("storage/{$this->image}"),
            'short_desc' => $this->short_desc,
            'desc' => $this->desc,
            'height' => $this->height,
            'width' => $this->width,
            'size' => $this->size,
            'created_at_fa' => $this->created_at_fa,
            'updated_at_fa' => $this->updated_at_fa,
        ];
    }
}
