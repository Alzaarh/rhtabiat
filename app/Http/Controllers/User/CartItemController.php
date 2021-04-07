<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartProduct;
use App\Http\Resources\ProductItemResource;

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
}
