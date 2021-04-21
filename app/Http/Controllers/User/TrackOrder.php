<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrder extends Controller
{
    public function __invoke()
    {
        request()->validate(['order_code' => 'required|string|exist:orders,code']);

        $order = Order::find(request()->order_code);

        switch ($order->status) {
//            case Order::STATUS_LIST['']
        }
    }
}
