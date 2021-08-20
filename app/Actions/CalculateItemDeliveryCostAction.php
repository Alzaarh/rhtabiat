<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;

class CalculateItemDeliveryCostAction
{
    public function execute(int $unit, float $weight, int $quantity, int $provinceId): int
    {
        $cost = 0;

        if ($unit === Product::UNITS['kilogram']) {
            $weight += 0.15;
            $cost = $provinceId === Order::KHORASAN_PROVINCE_ID ? $weight * 9800 : $weight * 14000;
        } else {
            $cost = 20000;
        }

        return $cost;
    }
}
