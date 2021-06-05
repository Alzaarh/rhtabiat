<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->create($request->validated());
        return response()->json(['message' => 'محصول با موفقیت ایجاد شد'], 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($request->validated(), $product);
        return response()->json(['message' => 'محصول با موفقیت به روزرسانی شد']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'محصول با موفقیت جذف شد']);
    }
}
