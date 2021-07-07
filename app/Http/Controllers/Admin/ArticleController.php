<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function store(StoreArticleRequest $request)
    {
        if ($request->user()->role === Admin::ROLES['admin']) {
            $data['is_verified'] = true;
        }
        $article = new Article($request->validated());
        $request->user()->articles()->save($article);
        return response()->json(['message' => 'مقاله با موفقیت ایجاد شد'], 201);
    }

    public function update(StoreArticleRequest $request, Article $article)
    {
        if ($request->user()->role === Admin::ROLES['admin']) {
            $article->is_verified = true;
        }
        $article->title = $request->title;
        $article->body = $request->body;
        $article->meta = $request->meta;
        $article->article_category_id = $request->article_category_id;
        $article->is_verified = false;
        $article->image_id = $request->image_id;
        $article->save();
        return response()->json(['message' => 'مقاله با موفقیت به روزرسانی شد']);
    }

    public function destroy(Article $article)
    {
        Gate::authorize('destroy-article', $article);
        \DB::table('articles')->where('id', $article->id)->delete();
        return response()->json(['message' => 'مقاله با موفقیت حذف شد']);
    }
}
