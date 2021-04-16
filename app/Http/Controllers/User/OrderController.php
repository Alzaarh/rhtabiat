<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\EmptyCart;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param  StoreOrderRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->handleNewOrder($request->validated());

        if (auth('user')->check()) {
            EmptyCart::dispatchSync(request()->user()->cart);
        }

        return response()->json(['message' => 'Order created', 'order' => $order], 201);
    }
}
