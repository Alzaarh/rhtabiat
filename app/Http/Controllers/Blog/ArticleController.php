<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\SingleArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $query = Article::query();

        if (request()->filled('article_category_id')) {
            $query->where(
                'article_category_id',
                request()->article_category_id
            );
        }

        if (request()->filled('search')) {
            $query->where('title', 'like', '%' . request()->search . '%');
        }

        return ArticleResource::collection($query->paginate(request()->count));
    }

    public function show(Article $article)
    {
        return new SingleArticleResource($article);
    }
}
