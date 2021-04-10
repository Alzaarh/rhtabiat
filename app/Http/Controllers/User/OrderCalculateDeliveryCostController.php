<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\OrderService;

class OrderCalculateDeliveryCostController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(OrderService $orderService)
    {
        request()->validate([
            'province_id' => 'required|exists:provinces,id',
            'total_weight' => 'required|min:0',
        ]);

        return response()->json([
            'data' => [
                'delivery_cost' => $orderService->calcDeliveryCost(
                    request()->province_id,
                    request()->total_weight
                ),
            ],
        ]);
    }
}
