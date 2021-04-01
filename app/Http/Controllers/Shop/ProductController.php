<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    /**
     * Instance of productService class.
     *
     * @var ProductService $productService
     */
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index()
    {
        $query = Product::query();
        if (request()->query('sort_by') === 'lowest_price') $query = $this->productService->orderByPrice('asc');
        if (request()->query('sort_by') === 'highest_price') $query = $this->productService->orderByPrice('desc');
        if (request()->query('sort_by') === 'latest') $query = Product::latest();
        return new ProductCollection($query->paginate(request()->count));
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Product $product): \Illuminate\Http\Resources\Json\JsonResource
    {
        return new ProductResource($product);
    }
}
