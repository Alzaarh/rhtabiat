<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\StoreGuestOrderRequest;
use App\Services\InitiateWithZarinpalService;
use App\Services\OrderService;

class CreateOrderForGuestController
{
    public function __invoke(StoreGuestOrderRequest $request, OrderService $orderService, InitiateWithZarinpalService $initiateWithZarinpal)
    {
        $order = $orderService->create($request->validated());

        $result = $initiateWithZarinpal->handle($order->price, $request->input('email', ''), $request->input('mobile'));

        if (empty($result['errors']) && $result['data']['code'] == 100) {
            $order->transactions()->create([
                'amount' => $order->price,
                'authority' => $result['data']['authority'],
            ]);

            return response()->json([
                'message' => __('messages.order.store'),
                'data' => [
                    'redirect_url' => config('app.zarinpal.redirect_url') . $result['data']['authority'],
                ],
            ], 201);
        }
    }
}
