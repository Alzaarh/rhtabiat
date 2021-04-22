<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::latest();

        request()->whenFilled('sort_by', function ($sortBy) use ($query) {
            switch ($sortBy) {
                case 'lowest_price':
                    $query->orderByPrice('asc');
                    break;
                case 'highest_price':
                    $query->orderByPrice('desc');
                    break;
                case 'highest_rated':
                    $query->orderByScore();
                    break;
            }
        });

        request()->whenFilled(
            'search',
            fn($term) => $query->where('name', 'like', '%'.$term.'%')
        );

        request()->whenFilled(
            'min_price',
            fn ($price) => $query->wherePrice('>=', $price)
        );

        request()->whenFilled(
            'max_price',
            fn ($price) => $query->wherePrice('<=', $price)
        );

        request()->whenFilled(
            'category_id',
            fn ($categoryId) => $query->whereCategoryId($categoryId)
        );

        return IndexProductResource::collection(
            $query->paginate(request()->count)
        );
    }

    public function show(Product $product)
    {
        $product->load(['category.parent', 'comments']);

        return new SingleProductResource($product);
    }
}
