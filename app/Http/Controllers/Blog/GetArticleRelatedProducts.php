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
        return IndexProduct::collection(Product::take(10)->get(request()->count));
    }
}
