<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $order = Order::whereCode($value)->first();
                    if (empty($order)) {
                        $fail('کد سفارش معتبر نیست');
                    }
                    if ($order->status !== Order::STATUS['not_paid']) {
                        $fail('سفارش پرداخت شده است');
                    }
                    if (now()->diffInHours($order->created_at) > 24) {
                        $fail('سفارش منقضی شده است');
                    }
                }
            ]
        ];
    }

    public function attributes()
    {
        return [
            'order_code' => 'کد سفارش',
        ];
    }
}
