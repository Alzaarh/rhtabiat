<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\IndexProductRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CommentResource;

class ProductController extends Controller
{
    public function index(IndexProductRequest $request)
    {
        $products = $request->whenHas('featured', function () use ($request) {
            return Product::getFeatured($request->count);
        });
        return ProductResource::collection($products);
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => [
                'required',
                function ($attr, $value, $fail) {
                    $category = Category::find($value);
                    if (!$category) {
                        return $fail('Invalid category_id');
                    }
                    if (Category::where('parent_id', $category->id)->exists()) {
                        return $fail('Invalid category_id');
                    }
                },
            ],
            'short_desc' => 'required|string|max:5000',
            'desc' => 'required|string|max:20000',
            'icon' => 'file|image|max:5120',
            'rooy' => 'array',
            'rooy.*.value' => 'required',
            'rooy.*.price' => 'required|integer|min:0',
            'plastic' => 'array',
            'plastic.*.value' => 'required',
            'plastic.*.price' => 'required|integer|min:0',
            'sizes' => 'array',
            'sizes.*.value' => 'required',
            'sizes.*.price' => 'required|integer|min:0',
        ]);
        $path;
        if ($request->hasFile('icon')) {
            $path = $request->icon->store('images');
        }
        $data['icon'] = $path;
        $product = Product::create($data);
        $sizes = [];
        if ($request->rooy) {
            foreach ($request->rooy as $item) {
                array_push($sizes, new ProductSize([
                    'container' => 'rooy',
                    'value' => $item['value'],
                    'price' => $item['price']
                ]));
            }
            $product->productSizes()->saveMany($sizes);
        }
        if ($request->plastic) {
            foreach ($request->plastic as $item) {
                array_push($sizes, new ProductSize([
                    'container' => 'plastic',
                    'value' => $item['value'],
                    'price' => $item['price']
                ]));
            }
        }
        if ($request->sizes) {
            foreach ($request->sizes as $item) {
                array_push($sizes, new ProductSize([
                    'value' => $item['value'],
                    'price' => $item['price']
                ]));
            }
        }
        $product->productSizes()->saveMany($sizes);
        return response()->json(['data' => $product], 201);
    }

    public function show(Request $request, Product $product)
    {
        $request->merge(['withDetails' => true]);
        return new ProductResource($product);
    }

    public function getFeatured(Request $request)
    {
        return ProductResource::collection(
            $this->product->getFeatured($request->count)
        );
    }

    public function getRelated(Request $request, Product $product)
    {
        $request->validate(['count' => 'integer|min:1|max:18']);
        return ProductResource::collection(
            $product->getRelated($request->count)
        );
    }

    public function addComment(StoreCommentRequest $request, Product $product)
    {
        $product->createComment($request->validated());
        return response()->json(['message' => 'success']);
    }

    public function getComments(Product $product)
    {
        return CommentResource::collection($product->comments);
    }
}
