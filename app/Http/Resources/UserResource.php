<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'detail' => new UserDetailResource($this->whenLoaded('detail')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
