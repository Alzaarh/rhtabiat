<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        request()->validate(['sort_by' => 'in:lowest_price,highest_price,latest,highest_rated']);

        $query = Product::query();

        if (request()->has('sort_by')) {
            $this->productService->orderBy($query, request()->query('sort_by'));
        }
        if (request()->has('search')) {
            return new ProductCollection($this->productService->handleSearch());
        }
        if (request()->has('min_price')) {
            $query->wherePriceIsGreater(request()->min_price);
        }
        if (request()->has('max_price')) {
            $query->wherePriceIsLess(request()->max_price);
        }
        if (request()->has('category_id')) {
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
