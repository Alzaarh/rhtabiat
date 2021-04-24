<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class TrackOrder extends FormRequest
{
    public function rules()
    {
        return [
            'order_code' => [
                'required',
                function ($attr, $value, $fail) {
                    if (Order::whereCode($value)->paid()->doesntExist()) {
                        $fail('سفارشی با این شناسه وجود ندارد');
                    }
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'order_code' => 'شناسه سفارش',
        ];
    }
}
