<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public function store(StoreProductCategoryRequest $request)
    {
        ProductCategory::create($request->validated());
        return response()->json(['message' => 'دسته بندی با موفقیت ایجاد شد'], 201);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $category)
    {
        $category->update($request->validated());
        return response()->json(['message' => 'دسته بندی با موفقیت به روزرسانی شد']);
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();
        return response()->json(['message' => 'دسته بندی با موفقیت حذف شد']);
    }
}
