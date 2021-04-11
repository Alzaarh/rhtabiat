<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductItem;
use Illuminate\Support\Facades\DB;
use App\Models\Address;

class OrderService
{
    public function handleNewOrder($orderData) : void
    {
        DB::transaction(function () use ($orderData) {
            $address = $this->handleOrderAddress($orderData);

            dd(collect($orderData->get('products'))->get(0));
            $orderProducts = collect($orderData->get('products'));
            $productItems = ProductItem::with('product')
                ->whereIn('id', $orderProducts->pluck('id'))
                ->get();
            
            $orderProducts->each(function ($orderProduct) use ($productItems) {
                $orderProduct->put('price', $productItems->first(function ($productItem) use ($orderProduct) {
                    return $productItem->id === $orderProduct->get('id');
                })->price);
                $orderProduct->put('off', $productItems->first(function ($productItem) use ($orderProduct) {
                    return $productItem->id === $orderProduct->get('id');
                })->product->off);
            });

            dd($orderProducts);
            $orderCost = $this->calcOrderCost($data);
            if ($orderCost >= 200000) {
                $deliveryCost = 0;
            } else {
                $deliveryCost = $this->calcDeliveryCost(
                    $address->province_id,
                    $this->calculateOrderWeight($request->items)
                );
            }
            $order = $address->orders()->save(new Order([
                'status' => Order::STATUS_LIST['not_paid'],
                'payment_method' => $request->payment_method,
                'code' => Order::generateCode(),
                'delivery_cost' => $deliveryCost,
            ]));
            $order->items()->attach($this->getItems($request->items));
        });
    }

    protected function handleOrderAddress($orderData) : Address
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

    public function calcDeliveryCost(int $provinceId, int $totalWeight): int
    {
        $provinceId === Order::WHITHIN_PROVINCE
            ? $totalWeight *= 9800
            : $totalWeight *= 14000;
        return ($totalWeight + 2500) * 1.1;
    }

    protected function calcOrderCost(array $data) : int
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

    protected function calcProductPrice($item, $qty)
    {
        return $productItem->price *
        ((100 - $productItem->product->off) / 100) *
        $items[$index]['quantity'];
    }
}
