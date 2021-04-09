<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductItem;
use Illuminate\Support\Facades\DB;
use App\Models\Address;

class OrderService
{
    public function handleNewOrder($data)
    {
        DB::transaction(function () use ($data) {
            $address = $this->handleOrderAddress($data);
            $orderCost = $this->calculateOrderCost($request->items);
            if ($orderCost >= 200000) {
                $deliveryCost = 0;
            } else {
                $deliveryCost = $this->calculateDeliveryCost(
                    $request->state,
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

    private function calculateDeliveryCost(string $state, int $totalWeight): int
    {
        if ($state === 'خراسان رضوی') {
            $totalWeight *= 9800;
        } else {
            $totalWeight *= 14000;
        }
        return ($totalWeight + 2500) * 1.1;
    }

    private function calculateOrderCost(array $items)
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
                return $carry + $productItem->price *
                    ((100 - $productItem->product->off) / 100) *
                    $items[$index]['quantity'];
            },
            0
        );
    }

    private function calculateOrderWeight(array $items)
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

    protected function handleOrderAddress(array $data) : Address
    {
        return filled($data['address_id'])
            ? Address::find($data['address_id'])
            : Address::create($data);
    }
}
