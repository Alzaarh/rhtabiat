<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProduct;
use App\Models\Product;

class GetSimilarProducts extends Controller
{
    public function __invoke(Product $product)
    {
        return IndexProduct::collection($product->getSimilar());
    }
}
