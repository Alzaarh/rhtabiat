<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class GetOrderDeliveryCostFormula extends Controller
{
    public function __invoke()
    {
        $p = Order::WITHIN_PROVINCE;
        return response()->json([
            'data' => [
                'function' => [
                    'arguments' => 'p,w',
                    'body' => "if (p === $p) {return (((w+0.15)*9800)+2500)*1.1} else {return (((w+0.15)*14000)+2500)*1.1}"
                ],
            ],
        ]);
    }
}
