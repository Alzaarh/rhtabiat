<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProductResource;
use App\Models\Product;

class IndexSpecialProduct extends Controller
{
    public function __invoke()
    {
        return IndexProductResource::collection(
            Product::hasDiscount()->take(request()->query('count', 10))->get()
        );
    }
}
