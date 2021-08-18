<?php

namespace App\Http\Controllers\Shop;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class GetBestSellingProductsController
{
    public function __invoke()
    {
        return ProductResource::collection(
            Product::whereIsBestSelling(true)
                ->take(request()->query('count', 10))
                ->get()
        );
    }
}
