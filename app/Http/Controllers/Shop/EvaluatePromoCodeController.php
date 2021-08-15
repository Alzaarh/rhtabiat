<?php

namespace App\Http\Controllers\Shop;

use App\Actions\EvaluatePromoCodeAction;
use App\Exceptions\NotUsablePromoCodeException;
use App\Http\Requests\EvaluatePromoCodeRequest;
use App\Models\PromoCode;

class EvaluatePromoCodeController
{
    public function __invoke(EvaluatePromoCodeRequest $request, EvaluatePromoCodeAction $action)
    {
        try {
            $discount = $action->execute(
                PromoCode::whereCode($request->input('promo_code'))->first(),
                $request->input('order_cost')
            );

            return response()->json(['data' => ['discount' => $discount]]);
        } catch (NotUsablePromoCodeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => '403',
            ], 403);
        }
    }
}
