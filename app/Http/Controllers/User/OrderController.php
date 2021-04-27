<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestOrder;
use App\Http\Requests\StoreOrder;
use App\Jobs\EmptyCart;
use App\Models\ProductItem;
use App\Services\OrderService;
use App\Services\ValidateOrderItemQuantityService;
use Illuminate\Validation\ValidationException;
use DB;

class OrderController extends Controller
{
    protected ValidateOrderItemQuantityService $validateOrderItemQuantityService;

    public function __construct(ValidateOrderItemQuantityService $validateOrderItemQuantityService)
    {
        $this->validateOrderItemQuantityService = $validateOrderItemQuantityService;
    }

    /**
     * @throws \Throwable
     */
    public function store(GuestOrder $request)
    {
        $orderItems = [];
        $productItems = ProductItem::with('product')
            ->find(array_column($request->products, 'id'));

        $productItems->each(function ($index, $productItem) use($orderItems) {
            if ($productItem->quantity < request()->products[$index]['quantity']) {
                throw ValidationException::withMessages(['products' => ['تعداد محصول انتخاب شده بیش از حد مجاز است']]);
            }
            $orderItems[] = [
                'product_id' => $productItem->product->id,
                'product_item_id' => $productItem->id,
                'quantity' => request()->products[$index]['quantity'],
                'off' => $productItem->product->off,
            ];
        });

        DB::transaction(function () {

        });
        return response()->json([
            'message' => __('messages.order.store'),
            'order' => $order,
        ], 201);
    }
}
