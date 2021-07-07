<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackOrder as Request;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class TrackOrder extends Controller
{
    public function __invoke(Request $request)
    {
        $order = Order::whereCode($request->order_code)
            ->first();

        return new OrderResource($order);
    }
}
