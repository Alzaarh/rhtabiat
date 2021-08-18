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
            'created_at_fa' => $this->getCreatedAt(),
            'updated_at_fa' => $this->updatedat_fa,
        ];
    }
}
