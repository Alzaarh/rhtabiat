<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;

class ProductCategoryController extends Controller
{
    /**
     * Instance of ProductCategoryService.
     * 
     * @var ProductCategoryService
     */
    private ProductCategoryService $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
    }

    public function store(StoreProductCategoryRequest $request)
    {
        return new ProductCategoryResource($this->productCategoryService->create($request));
    }

    public function update(StoreProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $this->productCategoryService->update($request, $productCategory);
        return new ProductCategoryResource($productCategory);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return response()->json(['message' => 'Success']);
    }
}
