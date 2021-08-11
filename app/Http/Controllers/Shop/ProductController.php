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
        $product = Product::query();

        request()->whenFilled('sort_by', function ($sortBy) use ($product) {
            switch ($sortBy) {
                case 'lowest_price':
                    $product->orderByPrice('asc');
                    break;
                case 'highest_price':
                    $product->orderByPrice('desc');
                    break;
                case 'highest_rated':
                    $product->orderByScore();
                    break;
            }
        });

        request()->whenFilled(
            'search',
            fn($term) => $product->where('name', 'like', '%'.$term.'%')
        );

        request()->whenFilled(
            'min_price',
            fn ($price) => $product->wherePrice('>=', $price)
        );

        request()->whenFilled(
            'max_price',
            fn ($price) => $product->wherePrice('<=', $price)
        );

        request()->whenFilled(
            'category_id',
            fn ($categoryId) => $product->whereCategoryId($categoryId)
        );

        // best selling filter
        if ($request->query('best_selling') === 'true') {
            $product->bestSelling();
        }

        // product with off filter
        if ($request->query('featured') === 'true') {
            $product->where('off', '>', 0);
        }

        return IndexProduct::collection(
            $product->paginate(request()->count)
        );
    }

    public function show(Product $product)
    {
        $product->load(['category.parent', 'comments', 'items']);

        return new SingleProductResource($product);
    }
}
