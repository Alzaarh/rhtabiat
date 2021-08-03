<?php


namespace App\Services;


use App\Models\Order;

class CalcOrderDeliveryCostService
{
    public function handle(int $price, int $provinceId, float $weight): int
    {
        $cost = 0;
        $weight += 0.15;
        if ($price < Order::DELIVERY_THRESHOLD) {
            $cost = $provinceId === Order::WITHIN_PROVINCE ? $weight * 9800 : $weight * 14000;
            return ($cost + 2500) * 1.1;
        }
        return 0;
    }
}
