<?php

namespace App\Services;

use App\Models\DiscountCode;

class DiscountCodeService
{
    public function calcDiscount(DiscountCode $code, int $orderCost): int
    {
        $off = 0;

        if (filled($code->min) && $code->min > $orderCost) {
            return 0;
        }

        if (filled($code->percent)) {
            $off = $orderCost * $code->percent / 100;
        }
        if (filled($code->value) && $code->value > $off) {
            $off = $code->value;
        }

        if (filled($code->max) && $off > $code->max) {
            $off = $code->max;
        }

        return $off;
    }
}
