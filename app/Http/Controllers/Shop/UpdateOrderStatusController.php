<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateOrderStatusController
{
    /**
     * Update order status.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function __invoke(Request $request, Order $order): JsonResponse
    {
        $request->validate(['status' => ['required', Rule::in(Order::STATUS)]]);

        $order->status = $request->input('status');
        $order->save();

        // TODO: send sms to user according to status

        return response()->json([
            'statusCode' => '200',
            'message' => __('messages.order.updateStatus'),
        ]);
    }
}
