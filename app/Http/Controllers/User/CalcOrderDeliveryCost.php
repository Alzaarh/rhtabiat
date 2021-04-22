<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalcDeliveryCost;
use App\Services\OrderService;

class CalcOrderDeliveryCost extends Controller
{
    public function __invoke(CalcDeliveryCost $request, OrderService $service)
    {
        return response()->json([
            'data' => [
                'delivery_cost' => $service->calcDeliveryCost(
                    $request->price,
                    $request->province,
                    $request->weight
                ),
            ],
        ]);
    }
}
