<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public function store(StoreProductCategoryRequest $request)
    {
        $data = $request->validated();

        $data['image'] = $request->image->store('images');

        ProductCategory::create($data);

        return response()->json(['message' => __('messages.resource.created', ['resource' => 'دسته بندی'])], 201);
    }

    public function update(StoreProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $data = $request->validated();

        $data['image'] = $request->image->store('images');

        Storage::delete($productCategory->image);

        $productCategory->update($data);

        return response()->json(['message' => __('messages.resource.updated', ['resource' => 'دسته بندی'])]);
    }

    public function destroy(ProductCategory $productCategory)
    {
        Storage::delete($productCategory->image);

        $productCategory->delete();

        return response()->json(['message' => __('messages.resource.deleted', ['resource' => 'دسته بندی'])]);
    }
}
