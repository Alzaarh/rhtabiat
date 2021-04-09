<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request)
    {
        if (auth('user')->check() && empty($request->address_id)) {
            return response()->json([
                'message' => 'Address_id must be present.'
            ], 400);
        }
        
        $this->orderService->handleNewOrder($request->validated());
        return response()->json(['message' => 'Success'], 201);
    }
}
