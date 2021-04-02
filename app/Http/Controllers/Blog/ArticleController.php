<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $query = Article::query();
        if (request()->has('latest')) {
            $query->latest();
        }
        if (request()->has('article_category_id')) {
            $query->where('article_category_id', request()->article_category_id);
        }
        return ArticleResource::collection($query->paginate(request()->count));
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }
}
