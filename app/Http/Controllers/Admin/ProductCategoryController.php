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
        $data = $request->validated();
        $data['image'] = $request->image->store('images');
        $data['image_mobile'] = $request->image_mobile->store('images');

        ProductCategory::create($data);

        return response()->json([
            'message' => 'دسته بندی با موفقیت ایجاد شد',
        ], 201);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $category)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->image->store('images');
            Storage::delete($category->image);
        }
        if ($request->hasFile('image_mobile')) {
            $data['image_mobile'] = $request->image_mobile->store('images');
            Storage::delete($category->image_mobile);
        }

        $category->update($data);
        return response()->json(['message' => 'دسته بندی با موفقیت به روزرسانی شد']);
    }

    public function destroy(ProductCategory $category)
    {
        Storage::delete($category->image);
        Storage::delete($category->image_mobile);

        $category->delete();
        return response()->json(['message' => 'دسته بندی با موفقیت حذف شد']);
    }
}
