<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(IndexProductRequest $request)
    {
        $query = Product::select('products.*')->with('image');

        $request->whenHas('sort_by', fn ($sortBy) => $this->productService->sortByKey($query, $sortBy));

        $request->whenHas('search', fn ($search) => $query->search($search));

        $request->whenHas('category_id', fn ($categoryId) => $query->whereCategoryId($categoryId));

        $request->whenHas('best_selling', fn () => $query->whereIsBestSelling(true));

        $request->whenHas('featured', fn () => $query->where('off', '>', 0));

        return ProductResource::collection($query->paginate($request->query('count', 10)));
    }

    public function show(Product $product)
    {
        $product->load('category.parent', 'comments', 'items', 'image');

        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->create($request->validated());

        return response()->json(['message' => __('messages.product.store')], 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($request->validated(), $product);

        return response()->json(['message' => __('messages.product.update')]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => __('messages.product.destroy')]);
    }
}
