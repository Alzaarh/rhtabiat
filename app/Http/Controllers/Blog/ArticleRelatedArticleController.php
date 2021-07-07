<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleRelatedArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        return ArticleResource::collection(
            Article::where('article_category_id', $article->article_category_id)
                ->where('id', '!=', $article->id)
                ->take(request()->query('count', 4))
                ->get()
        );
    }
}
