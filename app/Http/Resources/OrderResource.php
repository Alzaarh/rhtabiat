<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'price' => $this->price,
            'status_fa' => Order::STATUS_FA[$this->status],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
