<?php

namespace App\Services;

use App\Models\Address;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\ProductItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @throws \Throwable
     */
    public function createOrder($request): Order
    {
        $user = auth('user')->user();
        $address = Address::find($request->address);
        $items = $user ? $user->cart->prepared() : $this->getItems($request->products);
        $discount = DiscountCode::whereCode($request->discount_code)->value('id');

        return DB::transaction(function () use ($request, $user, &$address, $items, $discount) {
            if (empty($address)) {
                $address = filled($user)
                    ? $user->addresses()->create($request->validated())
                    : Address::create($request->validated());
            }
            $order = $address->orders()->create([
                'delivery_cost' => $this->calcDeliveryCost(
                    $this->calcOrderCost($items),
                    $address->province_id,
                    $this->calcOrderWeight($items),
                ),
                'discount_code_id' => $discount,
            ]);
            $order->items()->attach($items);
            return $order;
        });
    }

    protected function getItems(array $items): array
    {
        $orderItems = [];
        foreach ($items as $item) {
            $i = ProductItem::find($item['id']);
            $orderItems[$item['id']] = [
                'quantity' => $item['quantity'],
                'price' => $i->price,
                'off' => $i->product->off,
                'weight' => $i->weight,
                'product_id' => $i->product->id,
            ];
        }
        return $orderItems;
    }

    public function calcDeliveryCost(int $price, int $province, int $weight): int
    {
        $cost = 0;
        if ($price < 200000) {
            $cost = $province === Order::WITHIN_PROVINCE ? $weight * 9800 : $weight * 14000;
        }
        return ($cost + 2500) * 1.1;
    }

    protected function calcOrderCost(array $items): int
    {
        return array_reduce(
            $items,
            fn($carry, $i) => $carry + $i['price'] * (100 - $i['off']) / 100 * $i['quantity'],
            0
        );
    }

    protected function calcOrderWeight(array $items): int
    {
        return array_reduce(
            $items,
            fn($carry, $i) => $carry + $i['quantity'] * $i['weight'],
            0
        );
    }
}
