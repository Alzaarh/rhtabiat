<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Article;

class ArticleRelatedProductController extends Controller
{
    public function __invoke(Article $article)
    {
        $relatedProductCollection = Product::search($article->title)->get();
        if ($relatedProductCollection->count() < 3) {
            $relatedProductCollection = Product::whereIn(
                'category_id',
                $relatedProductCollection->pluck('category_id')
            )
                ->get()
                ->merge($relatedProductCollection);
        };
        return ProductResource::collection($relatedProductCollection);
    }
}
