<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;

class ArticleCategoryController extends Controller
{
    public function index()
    {
        return response()->json(['data' => ArticleCategory::all()]);
    }
}
