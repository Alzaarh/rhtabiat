<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProductResource;
use App\Models\Product;

class IndexBestSellingProduct extends Controller
{
    public function __invoke()
    {
        return IndexProductResource::collection(
            Product::withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->take(request()->query('count', 10))
                ->get()
        );
    }
}
