<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class GetOrderStatus extends Controller
{
    public function __invoke()
    {
        request()->validate(['order_code' => 'required|exists:orders,code']);
        $order = Order::whereCode(request()->order_code)->first();
        switch ($order->status) {
            case 1:
                $msg = 'سفارش در انتظار پرداخت می باشد';
                break;
            case 2:
                $msg = 'سفارش پرداخت و تایید شده است';
                break;
            case 3:
                $deliveryCode = $order->delivery_code;
                if ($deliveryCode) {
                    $msg = "سفارش به پست تحویل داده شده است و کد رهگیری پست $deliveryCode می باشد";
                } else {
                    $msg = "سفارش به پست تحویل داده شده است";
                }
                break;
            case 4:
                $msg = 'سفارش به مشتری تحویل داده شده است';
                break;
            case 5:
                $msg = 'سفارش رد شده است';
                break;
        }
        return response()->json(['message' => $msg]);
    }
}
