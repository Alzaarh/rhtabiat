<?php

namespace App\Http\Controllers\Shop;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class GetSimilarProductsController
{
    public function __invoke(Product $product)
    {
        return ProductResource::collection($product->getSimilar());
    }
}
