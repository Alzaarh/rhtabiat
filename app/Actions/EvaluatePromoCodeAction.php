<?php

namespace App\Actions;

use App\Exceptions\NotUsablePromoCodeException;
use App\Models\PromoCode;

class EvaluatePromoCodeAction
{
    public function execute(PromoCode $promoCode, int $orderCost): int
    {
        // todo... check promo_code is user_only

        if ($promoCode->isExpired()) {
            throw new NotUsablePromoCodeException('کد تخفیف منقضی شده است');
        }

        // todo... check if promo_code is available

        if (!$promoCode->IsPriceGraterThanMin($orderCost)) {
            throw new NotUsablePromoCodeException('حداقل مبلغ خرید رعایت نشده است');
        }

        if ($promoCode->off_percent) {
            $discount = $promoCode->off_percent * $orderCost / 100;
        } else {
            $discount = $promoCode->off_value;
        }

        if ($promoCode->max && $discount > $promoCode->max) {
            $discount = $promoCode->max;
        }

        return $discount;
    }
}
