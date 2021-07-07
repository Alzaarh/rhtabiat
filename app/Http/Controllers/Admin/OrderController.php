<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Http\Requests\AdminStoreOrderRequest;
use App\Services\ValidateGuestOrderService;
use App\Services\CalcOrderDeliveryCostService;
use App\Models\ProductItem;

class OrderController extends Controller
{
    protected ValidateGuestOrderService $validateGuestOrderService;

    protected CalcOrderDeliveryCostService $calcOrderDeliveryCostService;

    public function __construct(ValidateGuestOrderService $validateGuestOrderService, CalcOrderDeliveryCostService $calcOrderDeliveryCostService) 
    {
        $this->validateGuestOrderService = $validateGuestOrderService;
        $this->calcOrderDeliveryCostService = $calcOrderDeliveryCostService;
    }

    public function index()
    {
        return OrderResource::collection(Order::latest()->paginate());
    }

    public function store(AdminStoreOrderRequest $request)
    {
        $orderItems = $this->validateGuestOrderService->handle($request->products);
        $orderPrice = array_reduce($orderItems, fn($c, $i) => $i['price'] * (100 - $i['off']) / 100 * $i['quantity'] + $c, 0);
        $orderWeight = array_reduce($orderItems, fn($c, $i) => $i['weight'] * $i['quantity'] + $c, 0);
        \DB::transaction(function () use ($request, $orderPrice, $orderWeight, $orderItems) {
            $order = Order::create([
                'delivery_cost' => $this->calcOrderDeliveryCostService->handle($orderPrice, $request->province_id, $orderWeight),
                'status' => Order::STATUS['being_processed'],
            ]);
            $order->guestDetail()->create($request->validated());
            $order->items()->attach($orderItems);
            // Decrease items quantity
            foreach ($orderItems as $item) {
                ProductItem::whereId($item['product_item_id'])->decrement('quantity', $item['quantity']);
            }
        });
        return response()->json(['message' => 'سفارش با موفقیت ثبت شد'], 201);
    }
}
