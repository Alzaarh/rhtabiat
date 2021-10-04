<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\ProductItemResource;
use App\Models\ProductItem;

class CartItemController extends Controller
{
    public function store(UpdateCartRequest $request)
    {
        $cartItems = [];
        foreach ($request->input("product_items") as $productItem) {
            $cartItems[$productItem['id']] = [
                'quantity' => $productItem['quantity'],
            ];
        }
        $cart = $request->user()->cart;
        $cart->products()->detach();
        $cart->Products()->attach($cartItems);
        return response()->json(["message" => "به سبد خرید اضافه شد"]);
    }

    public function index()
    {
        return ProductItemResource::collection(
            request()->user()->cart->products->load('product')
        );
    }

    public function update(ProductItem $cartProduct)
    {
        request()->validate(['quantity' => 'required|min:1']);

        request()
            ->user()
            ->cart
            ->products()
            ->updateExistingPivot(
                $cartProduct->id,
                ['quantity' => request()->quantity]
            );

        return response()->json(['message' => 'Success']);
    }

    public function destroy(ProductItem $cartProduct)
    {
        request()
            ->user()
            ->cart
            ->products()
            ->detach($cartProduct->id);

        return response()->json(['message' => 'Success']);
    }
}
