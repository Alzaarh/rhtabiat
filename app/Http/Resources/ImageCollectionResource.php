<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageCollectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'alt' => $this->alt,
            'url' => $this->is_server_serve ? url('storage/' . $this->url) : config('app.webapp_domain') . $this->url,
            'group' => $this->group,
        ];
    }
}
