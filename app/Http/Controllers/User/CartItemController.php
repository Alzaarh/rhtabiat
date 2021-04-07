<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartProduct;
use App\Http\Resources\ProductItemResource;
use App\Models\ProductItem;

class CartItemController extends Controller
{
    public function store(StoreCartProduct $request)
    {
        $cartProducts = [];
        foreach ($request->products as $product) {
            $cartProducts[$product['id']] = [
                'quantity' => $product['quantity'],
            ];
        }
        
        request()->user()
            ->cart
            ->Products()
            ->attach($cartProducts);

        return response()->json(['message' => 'Success'], 201);
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
