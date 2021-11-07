<?php

namespace App\Http\Controllers\Shop;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Admin;

class OrderController
{
    public function index(Request $request): ResourceCollection
    {
        $order = Order::query();
        if ($request->user()->role === Admin::ROLES["discount_generator"]) {
            $order->where("referer_id", $request->user()->id);
        }
        $request->whenHas('status', fn (int $status) => $order->filter($status));

        $request->whenHas('orderCode', fn (string $orderCode) => $order->search($orderCode));

        return OrderResource::collection($order->paginate(10));
    }

    public function show(Order $order): OrderResource
    {
        $order->load('items.product', 'promoCode', 'address');

        // if ($order->purchasedByUser()->exists()) {
        //     $order->load('purchasedByUser');
        // } else {
        //     $order->load('purchasedByGuest.province');
        // }

        return new OrderResource($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(['message' => __('messages.destroy_order')]);
    }
}
