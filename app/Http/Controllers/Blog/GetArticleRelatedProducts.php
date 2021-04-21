<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexProductResource;
use App\Models\Article;
use App\Models\Product;

class GetArticleRelatedProducts extends Controller
{
    public function __invoke(Article $article)
    {
        return IndexProductResource::collection(
            Product::where(
                'name',
                'like',
                '%'.$article->title.'%'
            )
                ->get()
        );
    }
}
