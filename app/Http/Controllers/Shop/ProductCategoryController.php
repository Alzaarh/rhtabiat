<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;

class ProductCategoryController
{
    public function index()
    {
        return ProductCategoryResource::collection(
            ProductCategory::with(['products', 'children', 'image', 'imageMobile'])->get()
        );
    }

    public function show(ProductCategory $productCategory)
    {
        $productCategory->load('products', 'children', 'image', 'imageMobile', 'parent');

        return new ProductCategoryResource($productCategory);
    }

    public function store(StoreProductCategoryRequest $request)
    {
        ProductCategory::create($request->validated());

        return response()->json(['message' => __('messages.productCategory.store')], 201);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $productCategory->update($request->validated());

        return response()->json(['message' => __('messages.productCategory.update')]);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return response()->json(['message' => __('messages.productCategory.destroy')]);
    }
}
