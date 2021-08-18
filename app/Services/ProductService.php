<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * @throws Exception
     */
    public function create(array $data): Product
    {
        $productCategory = ProductCategory::find($data['category_id']);

        DB::beginTransaction();

        try {
            $product = $productCategory->products()->create($data);
            $product->items()->createMany($data['items']);

            DB::commit();

            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function sortByKey(Builder $query, string $key)
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

    private function orderByScore($query)
    {
        $query->selectRaw('products.*, avg(comments.score) as avg_score')
            ->join('comments', 'products.id', '=', 'comments.commentable_id')
            ->groupBy('products.id')
            ->orderBy('avg_score', 'desc');
    }
}
