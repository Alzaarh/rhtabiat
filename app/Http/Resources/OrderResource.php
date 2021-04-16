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
            'total_price' => $this->total_price,
            'status' => array_search($this->status, Order::STATUS_LIST_FA),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
