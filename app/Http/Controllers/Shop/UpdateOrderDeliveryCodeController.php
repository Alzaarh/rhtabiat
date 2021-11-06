<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateOrderDeliveryCodeController
{
    /**
     * Update order delivery code.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function __invoke(Request $request, Order $order): JsonResponse
    {
        $request->validate(['deliveryCode' => 'required|string|max:255']);

        $order->delivery_code = $request->input('deliveryCode');
        $order->save();

        $name = null;
        $phone = null;
        if ($order->address) {
            $phone = $order->address->mobile;
            $name = $order->address->name;
        } else {
            $phone = $order->guestOrder->mobile;
            $name = $order->guestOrder->name;
        }
        NotifyViaSms::dispatch(
            $phone,
            config('app.sms_patterns.order_post_office'),
            [
                'name' => $name,
                'code' => $request->input('deliveryCode'),
            ]
        );
        return response()->json([
            'statusCode' => '200',
            'message' => __('messages.order.updateDeliveryCode'),
        ]);
    }
}
