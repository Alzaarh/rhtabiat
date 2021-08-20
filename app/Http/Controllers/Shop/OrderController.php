<?php

namespace App\Http\Controllers\Shop;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderController
{
    /**
     * List orders.
     * Search through orders by code.
     * Filter order by status.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $order = Order::query();

        $request->whenHas('status', fn (int $status) => $order->filter($status));

        $request->whenHas('orderCode', fn (string $orderCode) => $order->search($orderCode));

        return OrderResource::collection($order->paginate(10));
    }

    public function show(Order $order): OrderResource
    {
        $order->load('items.product', 'promoCode');

        if ($order->purchasedByUser()->exists()) {
            $order->load('purchasedByUser');
        } else {
            $order->load('purchasedByGuest.province');
        }

        return new OrderResource($order);
    }
}
