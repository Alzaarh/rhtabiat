<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\SingleArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user('admin')) {
            $query = Article::withoutGlobalScope('available');
        } else {
            $query = Article::query();
        }
        $request->whenHas('verified', fn () => $query->whereIsVerified(false));
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

    public function show(Request $request, string $article)
    {
        if ($request->user('admin')) {
            $article = Article::withoutGlobalScope('available')
                ->whereSlug($article)
                ->firstOrFail();
        } else {
            $article = Article::whereSlug($article)->firstOrFail($article);
        }
        return new SingleArticleResource($article);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $request
            ->admin()
            ->articles()
            ->create($request->validated());
        return response()->json([
            'message' => __('messages.store_article'),
        ], 201);
    }

    public function update(UpdateArticleRequest $request, string $articleId): JsonResponse
    {
        $article = Article::withoutGlobalScope('available')
            ->whereId($articleId)
            ->firstOrFail();
        $article->update($request->validated());
        return response()->json(['message' => __('messages.update_article')]);

    }

    public function destroy(string $articleId)
    {
        $article = Article::withoutGlobalScope('available')
            ->whereId($articleId)
            ->firstOrFail();
        $article->delete();
        return response()->json(['message' => 'مقاله با موفقیت حذف شد']);
    }
}
