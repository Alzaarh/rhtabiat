<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexArticleRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(IndexArticleRequest $request)
    {
        $query = Article::query();
        $request->whenHas('latest', function () use ($request, &$articles) {
            $articles = Article::getLatest($request->query('count', 3));
        });
        $request->whenHas('article_category_id', function () use ($request, $query) {
            $query->where('article_category_id', $request->article_category_id);
        });
        return ArticleResource::collection($query->paginate($request->count));
    }

    public function show(Request $request, Article $article)
    {
        $request->merge(['withDetails' => true]);
        return new ArticleResource($article);
    }

    public function store(StoreArticleRequest $request)
    {
        $data = $request->except(['thumbnail', 'categoryId']);
        $data['blog_category_id'] = $request->categoryId;
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->thumbnail->store('images');
        }
        $article = auth('admin')->user()->articles()->save(new Article($data));
        return new ArticleResource($article);
    }

    public function update(StoreArticleRequest $request, Article $article)
    {
        $data = $request->except(['thumbnail', 'categoryId']);
        $data['blog_category_id'] = $request->categoryId;
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->thumbnail->store('images');
            Storage::delete($article->getAttributes()['thumbnail']);
        }
        $article->update($data);
        return new ArticleResource($article);
    }

    public function destroy(Article $article)
    {
        $admin = auth('admin')->user();
        if ($admin->isBoss() || $admin->id === $article->admin_id) {
            $article->delete();
            return new ArticleResource($article);
        } else {
            return response()->json(['message' => 'forbidden'], 403);
        }
    }

    public function addComment(StoreCommentRequest $request, Article $article)
    {
        $article->comments()->save(new Comment($request->validated()));
        return response()->json(['message' => 'OK']);
    }

    public function getLatest(Request $request)
    {
        $request->validate(['count' => 'integer|min:1|max:5']);
        $request->merge(['preview' => true]);
        return ArticleResource::collection(
            $this->article->getLatest($request->count)
        );
    }
}
