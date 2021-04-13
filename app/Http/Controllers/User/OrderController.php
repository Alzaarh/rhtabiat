<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\EmptyCart;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->handleNewOrder($request->validated());

        if (auth('user')->check()) {
            EmptyCart::dispatchSync(request()->user()->cart);
        }
        // $items = ProductItem::find(array_column($request->products, 'id'))
        //     ->load('product');
        // foreach ($request->products as $product) {
        //     abort_if(
        //         $items
        //             ->firstWhere('id', $product['id'])
        //             ->quantity < $product['quantity'],
        //         400
        //     );
        // }

        // DB::transaction(function () use ($request, $items) {
        //     $orderProductsPrice = 0;
        //     $orderProductsWeight = 0;
        //     $orderProducts = [];

        //     foreach ($request->products as $product) {
        //         $item = $items->firstWhere('id', $product['id']);

        //         $orderProductsPrice +=
        //             $item->price * (100 - $item->product->off) / 100
        //             * $product['quantity'];

        //         $orderProductsWeight += $item->weight * $product['quantity'];

        //         $orderProducts[$item->id] = [
        //             'price' => $item->price,
        //             'quantity' => $product['quantity'],
        //             'off' => $item->product->off,
        //             'weight' => $item->weight,
        //         ];
        //     }

        //     $orderDeliveryCost = $orderProductsPrice >= 200000
        //         ? 0
        //         : $this->orderService->calcDeliveryCost(
        //             $request->input('address.province_id'),
        //             $orderProductsWeight
        //         );

        //     $order = Order::create([
        //         'address_id' => $request->address_id
        //             ? Address::find($request->address_id)
        //             : Address::create([
        //                 'name' => $request->input('address.name'),
        //                 'company' => $request->input('address.company'),
        //                 'mobile' => $request->input('address.mobile'),
        //                 'phone' => $request->input('address.phone'),
        //                 'province_id' => $request->input('address.province_id'),
        //                 'city_id' => $request->input('address.city_id'),
        //                 'zipcode' => $request->input('address.zipcode'),
        //                 'address' => $request->input('address.address'),
        //             ])->id,
        //         'status' => Order::STATUS_LIST['not_paid'],
        //         'code' => Order::generateCode(),
        //         'delivery_cost' => $orderDeliveryCost,
        //         ]);

        //     $order->products()->attach($orderProducts);

        //     CreateTransaction::dispatchSync($order);
        // });

        return response()->json(['message' => 'Order created', 'order_id' => $order->id], 201);
    }
}
