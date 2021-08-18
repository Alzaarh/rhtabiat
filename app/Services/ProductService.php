<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductItem;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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

    /**
     * @param array $data
     * @throws Exception
     * @return Product
     */
    public function create(array $data): Product
    {
        if (isset($data['price'])) {
            $withCalculatedPrice = $this->calculateItemsPrice($data['items'], $data['price']);
        }
        DB::beginTransaction();

        try {
            $product = Product::create(Arr::except($data, ['items', 'has_container']));
            $product->items()->createMany(isset($withCalculatedPrice) ? $withCalculatedPrice : $data['items']);

            DB::commit();

            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function sortByKey(Builder $query, string $key): Builder
    {
        switch ($key) {
            case 'lowest_price':
                $query->orderByPrice('asc');
                break;
            case 'highest_price':
                $query->orderByPrice('desc');
                break;
            case 'highest_rated':
                // TODO
        }

        return $query;
    }

    public function update(array $data, Product $product): Product
    {
        if (isset($data['price'])) {
            foreach ($data['items'] as &$item) {
                $item['price'] = $item['weight'] * $data['price'];
            }
        }
        return DB::transaction(function () use (&$data, $product) {
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
                        if (!isset($data['items'][$i]['container']) && $data['items'][$i]['weight'] == $item->weight) {
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

    /**
     * Calculate price for each item based on base price.
     *
     * @param array $items
     * @param integer $basePrice
     * @return Collection
     */
    private function calculateItemsPrice(array $items, int $basePrice): Collection
    {
        return collect($items)->map(function ($item) use ($basePrice) {
            $item['price'] = $item['weight'] * $basePrice;
            return $item;
        });
    }
}
