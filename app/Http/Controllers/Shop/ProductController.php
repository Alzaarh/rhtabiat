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
        request()->validate(['sort_by' => 'in:lowest_price,highest_price,latest,highest_rated']);
        $query = Product::query();
        if (request()->has('sort_by')) $query = $this->productService->orderBy(request()->query('sort_by'));
        if (request()->has('search')) $query = Product::search(request()->query('search'));
        return new ProductCollection($query->paginate(request()->count));
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
