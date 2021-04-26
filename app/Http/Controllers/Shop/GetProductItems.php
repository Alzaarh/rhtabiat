<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductItemResource;
use App\Models\ProductItem;

class GetProductItems extends Controller
{
    public function __invoke()
    {
        request()->validate([
            'products' => 'required|array',
            'products.*' => 'required|exists:product_items,id',
        ]);

        return ProductItemResource::collection(
            ProductItem::with('product')
                ->find(request()->products)
        );
    }
}
