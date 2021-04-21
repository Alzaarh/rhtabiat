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

        if (request()->sort_by === 'lowest_price') {
            $query->orderByPrice('asc');
        }
        if (request()->sort_by === 'highest_price') {
            $query->orderByPrice('desc');
        }
        if (request()->filled('search')) {
            $query->where('name', 'like', '%'.request()->search.'%');
        }
        if (request()->filled('min_price')) {
            $query->wherePrice('>=', request()->min_price);
        }
        if (request()->filled('max_price')) {
            $query->wherePrice('<=', request()->max_price);
        }
        if (request()->filled('category_id')) {
            $query->whereCategoryId(request()->category_id);
        }

        return IndexProductResource::collection(
            $query->paginate(request()->count)
        );
    }

    public function show(Product $product)
    {
        return new SingleProductResource($product);
    }
}
