<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Instance of Product model.
     * 
     * @var Product
     */
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

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
     * Create the product and its items.
     *
     * @param Request $request
     * @return Product
     */
    public function create(Request $request): Product
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['image'] = $this->product->storeImage($request->image);
            $product = $this->product->create($data);
            $product->items()->createMany($data['items']);
            return $product;
        });
    }

    /**
     * Update the product and its items.
     *
     * @param Request $request
     * @param Product $product
     * @return Product
     */
    public function update(Request $request, Product $product): Product
    {
        return DB::transaction(function () use ($request, $product) {
            $data = $request->validated();
            $data['image'] = $this->product->storeImage($request->image);
            $product->update($data);
            collect($data['items'])->each(function ($item) use ($product) {
                empty($item['id'])
                    ? $product->items()->create($item)
                    : ProductItem::find($item['id'])->update($item);
            });
            return $product;
        });
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
