<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use Illuminate\Http\Request;

class RejectOrderController
{
    public function __invoke(Request $request, Order $order)
    {
        if ($request->input('reject') === 'true') {
            $order->reject();
            return response()->json([
                'status' => '200',
                'message' => __('messages.order.reject'),
            ]);
        }
    }
}
