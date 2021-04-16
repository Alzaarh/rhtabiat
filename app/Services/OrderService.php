<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\ProductItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function handleNewOrder($orderData): Order
    {
        if (auth('user')->check()) {
            return $this->createForUser($orderData);
        }
        return $this->createForGuest($orderData);
    }

    private function createForUser(array $orderData): Order
    {
        $this->validateForUser();

        $orderProducts = [];
        $cart = request()->user()->cart;

        $cart->products->each(
            function ($item) use (&$orderProducts) {
                $orderProducts[$item->id] = [
                    'price' => $item->price,
                    'quantity' => $item->pivot->quantity,
                    'off' => $item->product->off,
                    'weight' => $item->weight,
                ];
            }
        );

        return DB::transaction(
            function () use ($orderData, $orderProducts, $cart) {
                $order = request()->user()->orders()->save(
                    new Order(
                        [
                            'address_id' => $orderData['address_id'],
                            'status' => Order::STATUS_LIST['not_paid'],
                            'code' => Order::generateCode(),
                            'delivery_cost' => $this->calcDeliveryCost(
                                $cart->total_price,
                                Address::find($orderData['address_id'])->province_id,
                                $cart->total_weight
                            ),
                        ]
                    )
                );

                $order->products()->attach($orderProducts);

                return $order;
            }
        );
    }

    private function validateForUser(): void
    {
        abort_if(request()->user()->isCartEmpty(), 400);

        request()->user()->cart->products->load('product')->each(
            fn($item) => abort_if($item->pivot->quantity > $item->quantity, 400)
        );
    }

    public function calcDeliveryCost(int $price, int $province, int $weight): int
    {
        if ($price >= 200000) {
            return 0;
        }

        $weight = $province === Order::WHITHIN_PROVINCE ? $weight * 9800 : $weight * 14000;
        return ($weight + 2500) * 1.1;
    }

    private function createForGuest(array $orderData): Order
    {
        $items = $this->validateForGuest($orderData['products']);

        $orderProducts = [];
        foreach ($orderData['products'] as $item) {
            $productItem = $items->firstWhere('id', $item['id']);
            $orderProducts[$item['id']] = [
                'quantity' => $item['quantity'],
                'price' => $productItem->price,
                'off' => $productItem->product->off,
                'weight' => $productItem->weight,
                'product_id' => $productItem->product->id,
            ];
        }

        return DB::transaction(
            function () use ($orderData, $orderProducts) {
                $address = Address::create($orderData['address']);
                $order = Order::create(
                    [
                        'address_id' => $address->id,
                        'status' => Order::STATUS_LIST['not_paid'],
                        'code' => Order::generateCode(),
                        'delivery_cost' => $this->calcDeliveryCost(
                            $this->calcOrderCost($orderProducts),
                            $address->province_id,
                            $this->calcOrderWeight($orderProducts),
                        ),
                    ]
                );

                $order->products()->attach($orderProducts);

                return $order;
            }
        );
    }

    private function validateForGuest(array $products)
    {
        $items = ProductItem::with('product')->whereIn('id', array_column($products, 'id'))->get();

        $items->each(
            function ($item) use ($products) {
                abort_if(
                    $products[array_search($item->id, array_column($products, 'id'))]['quantity'] > $item->quantity,
                    400
                );
            }
        );

        return $items;
    }

    private function calcOrderCost(array $products): int
    {
        return array_reduce(
            $products,
            fn($carry, $product) => $carry + $product['price'] * (100 - $product['off']) / 100 * $product['quantity'],
            0
        );
    }

    private function calcOrderWeight(array $products): int
    {
        return array_reduce(
            $products,
            fn($carry, $product) => $carry + $product['quantity'] * $product['weight'],
            0
        );
    }
}
