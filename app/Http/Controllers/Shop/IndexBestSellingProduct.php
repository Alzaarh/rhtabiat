<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProduct;
use App\Models\Product;

class IndexBestSellingProduct extends Controller
{
    public function __invoke()
    {
        return IndexProduct::collection(
            Product::whereIsBestSelling(true)
                ->take(request()->query('count', 10))
                ->get()
        );
    }
}
