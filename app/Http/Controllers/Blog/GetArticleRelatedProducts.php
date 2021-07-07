<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProduct;
use App\Models\Article;
use App\Models\Product;

class GetArticleRelatedProducts extends Controller
{
    public function __invoke(Article $article)
    {
        $regex = implode('|', explode(' ', $article->title));
        return IndexProduct::collection(
            Product::whereRaw("name regexp '$regex'")
                ->take(request()->query('count', 10))
                ->get()
        );
    }
}
