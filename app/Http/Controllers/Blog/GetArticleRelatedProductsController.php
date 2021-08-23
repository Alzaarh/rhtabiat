<?php

namespace App\Http\Controllers\Blog;

use App\Http\Resources\ProductResource;
use App\Models\Article;
use App\Models\Product;

class GetArticleRelatedProductsController
{
    public function __invoke(Article $article)
    {
        $product = Product::query();

        foreach (explode(' ', $article->title) as $productName) {
            $product->orWhere('name', 'like', '%' . $productName);
        }

        return ProductResource::collection($product->take(10)->get());
    }
}
