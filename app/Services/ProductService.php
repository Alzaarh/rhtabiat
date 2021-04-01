<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * Sort the products based on some criteria.
     *
     * @param string $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderBy(string $criteria)
    {
        if ($criteria === 'lowest_price') return $this->orderByPrice('asc');
        if ($criteria === 'highest_price') return $this->orderByPrice('desc');
        if ($criteria === 'latest') return Product::latest();
        if ($criteria === 'highest_rated') return $this->orderByScore();
    }

    /**
     * Sort the products by price in asc or desc direction.
     *
     * @param string $dir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function orderByPrice(string $dir)
    {
        return Product::selectRaw('products.*')
            ->join('product_items', 'products.id', '=', 'product_items.product_id')
            ->orderBy('price', $dir)
            ->groupBy('products.id');
    }

    /**
     * Sort the products by score.
     *  
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function orderByScore()
    {
        return Product::selectRaw('products.*, avg(comments.score) as avg_score')
            ->join('comments', 'products.id', '=', 'comments.commentable_id')
            ->groupBy('products.id')
            ->orderBy('avg_score', 'desc');
    }
}
