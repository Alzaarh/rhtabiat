<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\EvaluatePromoCodeRequest;

class EvaluatePromoCodeController
{
    public function __invoke(EvaluatePromoCodeRequest $request)
    {
        return response()->json([
            'data' => ['discount' => $request->promoCodeDiscount()],
        ]);
    }
}
