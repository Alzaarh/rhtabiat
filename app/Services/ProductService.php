<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function orderBy($query, string $criteria)
    {
        if ($criteria === 'lowest_price') {
            $this->orderByPrice($query, 'asc');
        }
        if ($criteria === 'highest_price') {
            $this->orderByPrice($query, 'desc');
        }
        if ($criteria === 'latest') {
            $query->latest();
        }
        if ($criteria === 'highest_rated') {
            $this->orderByScore($query);
        }
    }

    public function handleSearch()
    {
        $productCollection = Product::search()->get();
        if (request()->min_price) {
            $filteredProductCollection = $productCollection->filter(
                fn ($product) =>
                $product
                    ->items
                    ->whereBetween('price', [request()->min_price, request()->max_price])
                    ->count() > 0
            )->values();
        }
        request()->merge(['product_search_found_count' => $productCollection->count()]);
        return $filteredProductCollection ?? $productCollection;
    }

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

    private function orderByPrice($query, string $dir)
    {
        $query->selectRaw('products.*')
            ->join('product_items', 'products.id', '=', 'product_items.product_id')
            ->orderBy('price', $dir)
            ->groupBy('products.id');
    }

    private function orderByScore($query)
    {
        $query->selectRaw('products.*, avg(comments.score) as avg_score')
            ->join('comments', 'products.id', '=', 'comments.commentable_id')
            ->groupBy('products.id')
            ->orderBy('avg_score', 'desc');
    }
}
