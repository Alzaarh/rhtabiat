<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class GetOrderStatus extends Controller
{
    public function __invoke()
    {
        request()->validate(['order_code' => 'required|exists:orders,code']);
        $status = Order::whereCode(request()->order_code)->value('status');
        switch ($status) {
            case 1:
                $msg = 'سفارش در انتظار پرداخت می باشد';
                break;
            case 2:
                $msg = 'سفارش پرداخت و تایید شده است';
                break;
            case 3:
                $msg = 'سفارش به پست تحویل داده شده است';
                break;
            case 4:
                $msg = 'سفارش به مشتری تحویل داده شده است';
                break;
        }
        return response()->json(['message' => $msg]);
    }
}
