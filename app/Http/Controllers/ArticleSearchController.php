<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['term' => 'required', 'count' => 'integer|between:1,15']);
        return ArticleResource::collection(
            Article::search($request->term)->paginate($request->count)
        );
    }
}
