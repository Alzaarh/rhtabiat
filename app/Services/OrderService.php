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
    }

    private function createForUser(array $orderData): Order
    {
        $this->validateForUser();

        $orderProducts = [];
        $cart = request()->user()->cart;

        $cart->products->each(function ($item) use (&$orderProducts) {
            $orderProducts[$item->id] = [
                'price' => $item->price,
                'quantity' => $item->pivot->quantity,
                'off' => $item->product->off,
                'weight' => $item->weight,
            ];
        });

        return DB::transaction(function () use ($orderData, $orderProducts, $cart) {
            $order = request()->user()->orders()->save(new Order([
                'address_id' => $orderData['address_id'],
                'status' => Order::STATUS_LIST['not_paid'],
                'code' => Order::generateCode(),
                'delivery_cost' => $this->calcDeliveryCost(
                    $cart->total_price,
                    Address::find($orderData['address_id'])->province_id,
                    $cart->total_weight
                ),
            ]));

            $order->products()->attach($orderProducts);

            return $order;
        });
    }

    private function validateForUser(): void
    {
        abort_if(request()->user()->isCartEmpty(), 400);

        request()->user()->cart->products->load('product')->each(fn ($item) =>
            abort_if($item->pivot->quantity > $item->quantity, 400));
    }

    protected function handleOrderAddress($orderData): Address
    {
        return $orderData->has('address_id')
        ? Address::find($orderData->get('address_id'))
        : Address::create($orderData->all());
    }

    protected function getOrderProducts($orderData)
    {
        $orderProducts = collect();

        if (auth('user')->check()) {
            request()
                ->user()
                ->cart
                ->products
                ->each(fn ($item, $orderProducts) => $orderProducts->push(collect([
                    'id' => $item->id,
                    'price' => $item->price,
                ])));
        } else {
            ProductItem::with('product')
                ->whereIn(
                    'id',
                    collect($orderData->get('products'))->pluck('id')
                )
                ->get()
                ->each(function ($item) use ($orderData, $orderProducts) {
                    $orderProducts->push(collect([
                        'id' => $item->id,
                        'price' => $item->price,
                        'off' => $item->product->off,
                        'quantity' => collect($orderData->get('products')),
                    ]));
                });
        }
    }

    public function calcDeliveryCost(int $price, int $province, int $weight): int
    {
        if ($price >= 200000) {
            return 0;
        }

        $weight = $province === Order::WHITHIN_PROVINCE ? $weight * 9800 : $weight * 14000;
        return ($weight + 2500) * 1.1;
    }

    protected function calcOrderCost(array $data): int
    {
        if (filled($data['address_id'])) {
            $products = request()->user()->cart->products;

            return $products->reduce(function ($carry, $productItem) {
                return $carry + $productItem->price *
                ((100 - $productItem->product->off) / 100) *
                $productItem->pivot->quantity;
            }, 0);
        } else {
            $products = ProductItem::with('product')
                ->whereIn('id', array_column($data['items'], 'id'))
                ->get();

            return $products->reduce(
                function ($carry, $productItem) use ($data) {
                    $index = array_search(
                        $productItem->id,
                        array_column($data['items'], 'id')
                    );
                    return $carry + $productItem->price *
                        ((100 - $productItem->product->off) / 100) *
                        $data['items'][$index]['quantity'];
                },
                0
            );
        }
    }

    protected function calcOrderWeight(array $items)
    {
        $productItemCollection = ProductItem::with('product')
            ->whereIn('id', array_column($items, 'id'))
            ->get();
        return $productItemCollection->reduce(
            function ($carry, $productItem) use ($items) {
                $index = array_search(
                    $productItem->id,
                    array_column($items, 'id')
                );
                return $carry + $productItem->weight * $items[$index]['quantity'];
            },
            0
        );
    }

    private function getItems(array $items)
    {
        $productItemCollection = ProductItem::with('product')
            ->whereIn('id', array_column($items, 'id'))
            ->get();
        $formattedItems = [];
        $productItemCollection->each(
            function ($productItem) use ($items, $formattedItems) {
                $index = array_search(
                    $productItem->id,
                    array_column($items, 'id')
                );
                array_push($formattedItems, [$productItem->id => [
                    'price' => $productItem->price,
                    'weight' => $productItem->weight,
                    'quantity' => $items[$index]['quantity'],
                    'product_id' => $productItem->product->id,
                ]]);
            }
        );
        return $formattedItems;
    }
}
