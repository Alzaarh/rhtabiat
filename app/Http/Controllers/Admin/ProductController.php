<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

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
        return response()->json(['message' => 'Product created'], 201);
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $this->productService->update($request->validated(), $product);
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Success']);
    }
}
