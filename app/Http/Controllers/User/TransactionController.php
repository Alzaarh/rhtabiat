<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store()
    {
        request()->validate(['order_id' => 'required|exists:orders,id']);

        $order = Order::find(request()->order_id);

        $isNotPaid = $order->status !== Order::STATUS_LIST['not_paid'];
        $hasValidTime = now()->diffInHours($order->created_at) > 24;

        if (auth('user')->check()) {
            abort_if($isNotPaid || $hasValidTime || $order->user_id !== request()->user()->id, 400);
        } else {
            abort_if($isNotPaid || $hasValidTime, 400);
        }
    }
}
