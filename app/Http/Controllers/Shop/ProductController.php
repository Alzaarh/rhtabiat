<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProduct;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

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

        if ($request->query('only_best_selling') === 'true') {
            $query->bestSelling();
        }

        return IndexProduct::collection(
            $query->paginate(request()->count)
        );
    }

    public function show(Product $product)
    {
        $product->load(['category.parent', 'comments', 'items']);

        return new SingleProductResource($product);
    }
}
