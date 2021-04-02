<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Instance of ProductService class.
     * 
     * @var ProductService
     */
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProductRequest  $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreProductRequest $request)
    {
        return new ProductResource($this->productService->create($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreProductRequest $request
     * @param  Product $Product
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        $this->productService->update($request, $product);
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
