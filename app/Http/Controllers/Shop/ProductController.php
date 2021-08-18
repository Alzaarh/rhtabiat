<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\IndexProduct;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductController extends Controller
{
    public function index(IndexProductRequest $request, ProductService $productService): ResourceCollection
    {
        $query = Product::select('*')->with('image');

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

    /**
     * Create product with items.
     *
     * @param StoreProductRequest $request
     * @param ProductService $productService
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request, ProductService $productService): JsonResponse
    {
        $productService->create($request->validated());

        return response()->json([
            'statusCode' => '201',
            'message' => __('messages.product.store'),
        ], 201);
    }
}
