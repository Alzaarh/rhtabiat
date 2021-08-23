<?php


namespace App\Http\Controllers\Blog;


use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateArticleVerifiedStatusController
{
    public function __invoke(Request $request, $articleId): JsonResponse
    {
        $article = Article::withoutGlobalScope('available')->findOrFail($articleId);
        $article->is_verified = $request->is_verified;
        $article->save();
        return response()->json(['message' => __('messages.update_article_status')]);
    }
}
