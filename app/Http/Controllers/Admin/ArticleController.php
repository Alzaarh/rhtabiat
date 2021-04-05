<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function store(StoreArticleRequest $request)
    {
        $article = new Article($request->validated());
        $article->handleImageUpload($request->image);
        $request->user()->articles()->save($article);
        return new ArticleResource($article);
    }

    public function update(StoreArticleRequest $request, Article $article)
    {
        $article->handleImageUpload($request->image);
        $article->title = $request->title;
        $article->slug = $request->slug;
        $article->body = $request->body;
        $article->meta = $request->meta;
        $article->article_category_id = $request->article_category_id;
        $article->save();
        return new ArticleResource($article);
    }
}
