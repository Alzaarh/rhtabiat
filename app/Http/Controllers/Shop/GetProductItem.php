<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductItemResource;
use App\Models\ProductItem;

class GetProductItem extends Controller
{
    public function __invoke(ProductItem $item)
    {
        $item->load('product');

        return new ProductItemResource($item);
    }
}
