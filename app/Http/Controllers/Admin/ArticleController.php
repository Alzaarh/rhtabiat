<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;

class ArticleController extends Controller
{
    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->image->store('images');
        }
        if ($request->user()->role === Admin::ROLES['admin']) {
            $data['is_verified'] = true;
        }
        $article = new Article($data);
        $request->user()->articles()->save($article);
        return response()->json(['message' => 'مقاله با موفقیت ایجاد شد'], 201);
    }

    public function update(StoreArticleRequest $request, Article $article)
    {
        if ($article->image) {
            Storage::delete($article->image);
        }
        if ($request->image) {
            $article->image = $request->image->store('images');
        }
        if ($request->user()->role === Admin::ROLES['admin']) {
            $article->is_verified = true;
        }
        $article->title = $request->title;
        $article->body = $request->body;
        $article->meta = $request->meta;
        $article->article_category_id = $request->article_category_id;
        $article->is_verified = false;
        $article->save();
        return response()->json(['message' => 'مقاله با موفقیت به روزرسانی شد']);
    }

    public function destroy(Article $article)
    {
        Gate::authorize('destroy-article', $article);

        $article->delete();
        
        return response()->json(['message' => 'مقاله با موفقیت حذف شد']);
    }
}
