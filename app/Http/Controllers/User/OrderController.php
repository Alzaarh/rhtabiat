<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\EmptyCart;
use App\Models\Address;
use App\Models\DiscountCode;
use App\Services\OrderService;
use DB;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreOrderRequest $request)
    {
        $order = null;
        $code = null;
        if ($request->has('discount_code')) {
            $code = DiscountCode::whereCode($request->discount_code)->first();
        }

        if (auth('user')->guest()) {
            $products = $this->orderService->getItems($request->products);

            DB::transaction(function () use (&$order, $request, $products, $code) {
                $address = Address::create($request->all());
                $order = $address->orders()->create([
                    'delivery_cost' => $this->orderService->calcDeliveryCost(
                        $this->orderService->calcOrderCost($products),
                        $address->province_id,
                        $this->orderService->calcOrderWeight($products),
                    ),
                    'discount_code_id' => filled($code) ? $code->id : null,
                ]);

                $order->products()->attach($products);
            });
        }

        if (auth('user')->check()) {
            EmptyCart::dispatchSync(request()->user()->cart);
        }

        return response()->json([
            'message' => __(
                'messages.resource.created',
                ['resource' => 'سفارش']
            ),
            'order' => $order,
        ], 201);
    }
}
