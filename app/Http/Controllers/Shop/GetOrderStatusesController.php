<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use Illuminate\Http\JsonResponse;

class GetOrderStatusesController
{
    public function __invoke(Order $order): JsonResponse
    {
        $statuses = [];

        foreach (Order::STATUS as $status) {
            $order->setStatus($status);
            array_push($statuses, [$order->translateStatus() => $status]);
        }

        return response()->json(['data' => $statuses]);
    }
}
