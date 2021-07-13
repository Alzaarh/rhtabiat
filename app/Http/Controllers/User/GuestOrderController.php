<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuestOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\CalcOrderDeliveryCostService;
use App\Services\InitiateWithZarinpalService;
use App\Services\ValidateGuestOrderService;
use DB;

class GuestOrderController extends Controller
{
    protected ValidateGuestOrderService $validateOrder;

    protected CalcOrderDeliveryCostService $calcDeliveryCost;

    protected InitiateWithZarinpalService $initiateWithZarinpal;

    public function __construct(
        ValidateGuestOrderService $validateOrder,
        CalcOrderDeliveryCostService $calcDeliveryCost,
        InitiateWithZarinpalService $initiateWithZarinpal
    ) {
        $this->validateOrder = $validateOrder;
        $this->calcDeliveryCost = $calcDeliveryCost;
        $this->initiateWithZarinpal = $initiateWithZarinpal;
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreGuestOrderRequest $request)
    {
        $orderItems = $this->validateOrder->handle($request->products);
        $orderPrice = array_reduce(
            $orderItems,
            fn($c, $i) => $i['price'] * (100 - $i['off']) / 100 * $i['quantity'] + $c,
            0
        );
        $orderWeight = array_reduce(
            $orderItems,
            fn($c, $i) => $i['weight'] * $i['quantity'] + $c,
            0
        );
        $orderPackagePrice = Product::find(array_column($orderItems, 'product_id'))
            ->reduce(fn ($carry, $product) => $carry + $product->package_price, 0);

        $authority = DB::transaction(function () use ($request, $orderPrice, $orderWeight, $orderItems, $orderPackagePrice) {
            $order = Order::create([
                'delivery_cost' => $this->calcDeliveryCost->handle(
                    $orderPrice,
                    $request->province_id,
                    $orderWeight
                ),
                'discount_code_id' => filled($request->discount_code) ? $request->discount_code->id : null,
                'package_price' => $orderPackagePrice,
            ]);
            if ($request->discount_code) {
                $code = $request->discount_code;
                $code->is_suspended = true;
                $code->save();
            }
            $order->guestDetail()->create($request->validated());
            $order->items()->attach($orderItems);
            $result = $this->initiateWithZarinpal->handle($order->price);
            if ($result['Status'] == 100) {
                $order->transactions()->create([
                    'amount' => $order->price,
                    'authority' => $result['Authority'],
                ]);
            }
            return $result['Authority'];
        });

        return response()->json([
            'message' => __('messages.order.store'),
            'data' => [
                'redirect_url' => config('app.zarinpal.redirect_url') . $authority,
            ],
        ], 201);
    }
}
