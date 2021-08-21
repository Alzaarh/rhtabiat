<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\PromoCode;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @throws Exception
     */
    public function create(array $orderData, PromoCode $promoCode = null): Order
    {
        DB::beginTransaction();
        try {
            $order = $promoCode
                ? $promoCode->orders()->create($orderData)
                : Order::create($orderData);
            $order->setGuestDetail($orderData);
            $order->setItems($this->getItems($orderData)->toArray());
            DB::commit();

            return $order;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function calculateDeliveryCost(array $orderData): int
    {
        $totalItemsPrice = $this->getItems($orderData)->reduce(
            fn ($carry, $item) => $carry + $item['price'] * $item['quantity'],
            0
        );

        if ($totalItemsPrice >= Order::FREE_DELIVERY_COST_PRICE) {
            return 0;
        }

        $totalDeliveryCost = $this->getItems($orderData)->reduce(
            fn ($carry, $item) => $carry + $this->CalculateItemDeliveryCost(
                $item['unit'],
                $item['weight'],
                $item['quantity'],
                $orderData['province_id']
            ),
            0
        );

        return $totalDeliveryCost;
    }

    private function calculatePackagePrice(array $productIds): int
    {
        return Product::find($productIds)
            ->reduce(
                fn ($carry, $product) => $carry + $product->package_price,
                0
            );
    }

    private function getItems(array $orderData): Collection
    {
        $items = collect();

        collect($orderData['products'])->each(function ($item) use ($items) {
            $foundItem = ProductItem::find($item['id']);
            $items->put($foundItem->getId(), [
                'id' => $foundItem->id,
                'quantity' => $item['quantity'],
                'weight' => $foundItem->getWeight(),
                'product_id' => $foundItem->getProduct()->getId(),
                'price' => $foundItem->getPrice(),
                'off' => $foundItem->product->getOff(),
                'unit' => $foundItem->getProduct()->getUnit(),
            ]);
        });

        return $items;
    }

    private function CalculateItemDeliveryCost(int $unit, float $weight, int $quantity, int $provinceId): int
    {
        $cost = 0;

        if ($unit === Product::UNITS['kilogram']) {
            $weight *= $quantity;
            $weight += 0.15;
            $cost = $provinceId === Order::KHORASAN_PROVINCE_ID ? $weight * 9800 : $weight * 14000;
            $cost += 2500;
            $cost *= 1.1;
        } else {
            $cost = 20000;
        }

        return $cost;
    }
}
