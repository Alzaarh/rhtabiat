<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return ProductCategoryResource::collection(ProductCategory::with(['products', 'children'])->get());
    }

    public function show(ProductCategory $productCategory)
    {
        return new ProductCategoryResource($productCategory);
    }
}
