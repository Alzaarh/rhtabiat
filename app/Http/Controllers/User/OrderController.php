<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\EmptyCart;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $this->service->createOrder($request);

        if (auth('user')->check()) {
            EmptyCart::dispatchSync(request()->user()->cart);
        }

        return response()->json([
            'message' => __('messages.order.store'),
            'order' => $order,
        ], 201);
    }
}
