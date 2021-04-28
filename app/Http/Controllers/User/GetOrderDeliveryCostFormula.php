<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalcDeliveryCost;
use App\Models\Order;
use App\Services\OrderService;

class GetOrderDeliveryCostFormula extends Controller
{
    public function __invoke()
    {
        request()->validate([
            'province_id' => ['required', 'exists:provinces,id'],
            'weight' => ['required', 'integer', 'min:1'],
        ]);

        $p = Order::WITHIN_PROVINCE;
        return response()->json([
            'data' => [
                'function' => [
                    'arguments' => 'provinceId,weight',
                    'body' => "if (provinceId === $p) {return (((weight+0.15)*9800)+2500)*1.1} else {return (((weight+0.15)*14000)+2500)*1.1}"
                ],
            ],
        ]);
    }
}
