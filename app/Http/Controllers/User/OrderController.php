<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Jobs\NotifyViaSms;

class OrderController extends Controller
{
    public function index()
    {
        if (request()->user()->role === Admin::ROLES['writer']) {
            abort(403);
        }
        return OrderResource::collection(Order::latest()->paginate(request()->query('count', 10)));
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function update(Order $order)
    {
        if ($order->status === Order::STATUS['being_processed']) {
            request()->validate(['delivery_code' => 'required']);
            $order->status = Order::STATUS['in_post_office'];
            $order->delivery_code = request()->delivery_code;
            $order->save();
            if ($order->forGuest()) {
                $phone = $order->guestDetail->mobile;
                $name = $order->guestDetail->name;
            }
            NotifyViaSms::dispatchSync(
                $phone, 
                config('app.sms_patterns.order_post_office'), 
                ['name' => $name, 'code' => request()->delivery_code]
            );
        } elseif ($order->status === Order::STATUS['in_post_office']) {
            $order->status = Order::STATUS['delivered'];
            $order->save();
        }

        return response()->json(['message' => 'سفارش با موفقیت به روزرسانی شد']);
    }
}
