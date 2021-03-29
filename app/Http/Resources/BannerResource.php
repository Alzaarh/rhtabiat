<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'image' => $this->image,
            'linkText' => $this->link_text,
            'linkDest' => $this->link_dest,
            'isActive' => $this->when(
                auth('admin')->check() && $request->user()->isAdmin(), 
                $this->is_active
            ),
        ];
    }
}
