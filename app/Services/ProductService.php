<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * Sort the products by price in asc or desc direction.
     *
     * @param string $dir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderByPrice(string $dir): \Illuminate\Database\Eloquent\Builder
    {
        return Product::selectRaw('products.*')
            ->join('product_items', 'products.id', '=', 'product_items.product_id')
            ->orderBy('price', $dir)
            ->groupBy('products.id');
    }
}
