<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            'phone' => $this->phone,
            'detail' => new UserDetailResource($this->detail),
            "order_count" => \DB::table("orders")->join(
                "addresses",
                "address_id",
                "=",
                "addresses.id"
            )->where("user_id", $this->id)->count(),
            'created_at' => $this->created_at,
            'updated _at' => $this->updated_at,
        ];
    }
}
