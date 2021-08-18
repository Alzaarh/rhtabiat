<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function index(IndexProductRequest $request, ProductService $productService)
    {
        $query = Product::select('products.*')->with('image');

        $request->whenHas('sort_by', fn ($sortBy) => $productService->sortByKey($query, $sortBy));

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

    public function store(StoreProductRequest $request, ProductService $productService)
    {
        $productService->create($request->validated());

        return response()->json([
            'statusCode' => '201',
            'message' => __('messages.product.store'),
        ], 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'statusCode' => '200',
            'message' => __('messages.product.destroy'),
        ]);
    }
}
