<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleRelatedArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        $relatedArticleCollection = Article::where(
            'article_category_id',
            $article->article_category_id
        )
            ->take(request()->query('count', 4))
            ->get();
        if ($relatedArticleCollection->count() < 4) {
            $relatedArticleCollection = Article::inRandomOrder()
                ->take(4 - $relatedArticleCollection->count())
                ->get()
                ->merge($relatedArticleCollection);
        }
        return ArticleResource::collection($relatedArticleCollection);
    }
}
