<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'phone' => $this->phone,
            'detail' => new UserDetailResource($this->detail),
            'created_at' => $this->created_at,
            'updated _at' => $this->updated_at,
        ];
    }
}
