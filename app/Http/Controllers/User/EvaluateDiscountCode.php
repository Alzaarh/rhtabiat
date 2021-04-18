<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluateDiscountCodeRequest;
use App\Models\DiscountCode;
use App\Services\DiscountCodeService;

class EvaluateDiscountCode extends Controller
{
    public function __invoke(
        EvaluateDiscountCodeRequest $request,
        DiscountCodeService $discountCodeService
    ) {
        return response()->json([
            'data' => [
                'off' => $discountCodeService->calcDiscount(
                    DiscountCode::whereCode($request->discount_code)->first(),
                    $request->order_cost
                )
            ],
        ]);
    }
}
