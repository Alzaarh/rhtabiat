<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Models\Order;

class ReturnRequestController extends Controller
{
    public function store()
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits:11'],
            'order_code' => [
                'required',
                function ($attr, $value, $fail) {
                    $orderId = Order::whereCode($value)
                        ->whereIn('status', [Order::STATUS['in_post_office'], Order::STATUS['delivered']])
                        ->value('id');
                    if (!$orderId) {
                        return $fail('کدسفارش معتبر نیست');
                    }
                    request()->merge(['order_id' => $orderId]);
                },
            ],
            'email' => ['required', 'email', 'max:255'],
            'reason' => ['required', 'string', 'max:64000'],
        ]);
        
        ReturnRequest::create(request()->all());
        return response()->json(['message' => 'درخواست مرجوعی با موفقیت ثبت شد'], 201);
    }
}
