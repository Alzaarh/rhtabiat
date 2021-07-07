<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'parent' => new self($this->parent),
            'children' => $this->when(
                $request->has('withChildren'), $this->children
            ),
        ];
    }
}
