<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\StoreGuestOrderRequest;
use App\Services\InitiateWithZarinpalService;
use App\Services\OrderService;

class CreateOrderForGuestController
{
    public function __invoke(StoreGuestOrderRequest $request, OrderService $orderService, InitiateWithZarinpalService $initiateWithZarinpal)
    {
        $order = $orderService->create($request->orderData(), $request->promoCode());

        return response()->json(['message' => $order->getPrice()], 400);
        // $result = $initiateWithZarinpal->handle($order->getPrice(), $request->input('email', ''), $request->input('mobile'));

        // if (empty($result['errors']) && $result['data']['code'] == 100) {
        //     $order->transactions()->create([
        //         'amount' => $order->getPrice(),
        //         'authority' => $result['data']['authority'],
        //     ]);

        //     return response()->json([
        //         'message' => __('messages.order.store'),
        //         'data' => [
        //             'redirect_url' => config('app.zarinpal.redirect_url') . $result['data']['authority'],
        //         ],
        //     ], 201);
        // }

        // return response()->json(['data' => $result], 400);
    }
}
