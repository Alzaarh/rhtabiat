<?php

namespace App\Services;

use App\Models\Order;

class IdpayPayment
{
    public function pay(Order $order)
    {
        $params = [
            'order_id' => $order->id,
            'amount' => $order->price * 10,
            'name' => $order->guestOrder ? $order->guestOrder->name : '',
            'phone' => $order->guestOrder ? $order->guestOrder->mobile : '',
            'mail' => '',
            'desc' => 'خرید از فروشگاه '.config('app.name'),
            'callback' => config('app.payment_callback_url'),
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-API-KEY: 6a7f99eb-7c20-4412-a972-6dfb7cd253a4',
        'X-SANDBOX: 1'
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }
}