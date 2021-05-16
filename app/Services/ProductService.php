<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
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

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $data['image'] = request()->image->store('images');
            $product = Product::create($data);
            $product->items()->createMany($data['items']);
            return $product;
        });
    }

    public function update(array $data, Product $product): Product
    {
        Storage::delete($product->image);

        return DB::transaction(function () use (&$data, $product) {
            $data['image'] = request()->image->store('images');
            $product->update($data);
            $product->items->each(function ($item) use (&$data) {
                if ($item->container) {
                    for ($i = 0; $i < count($data['items']); $i++) {
                        if ($data['items'][$i]['container'] == $item->container && $data['items'][$i]['weight'] == $item->weight) {
                            $item->price = $data['items'][$i]['price'];
                            $item->quantity = $data['items'][$i]['quantity'];
                            $item->save();
                            $data['items'][$i]['sw'] = true;
                            return;
                        }
                    }
                } else {
                    for ($i = 0; $i < count($data['items']); $i++) {
                        if (!is_set($data['items'][$i]['container']) && $data['items'][$i]['weight'] == $item->weight) {
                            $item->price = $data['items'][$i]['price'];
                            $item->quantity = $data['items'][$i]['quantity'];
                            $item->save();
                            $data['items'][$i]['sw'] = true;
                            return;
                        }
                    }
                }
                $item->delete();
            });
            foreach ($data['items'] as $i) {
                if (!isset($i['sw'])) {
                    $product->items()->create($i);
                }
            }
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
