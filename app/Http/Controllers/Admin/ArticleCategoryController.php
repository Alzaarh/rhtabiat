<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleCategoryController extends Controller
{
    public function store()
    {
        request()->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:article_categories|max:255',
        ]);
        return response()->json([
            'data' => ArticleCategory::create(request()->all()),
        ], 201);
    }

    public function update(ArticleCategory $articleCategory)
    {
        request()->validate([
            'name' => 'required|max:255',
            'slug' => [
                'required',
                Rule::unique('article_categories')->ignore($articleCategory),
                'max:255',
            ],
        ]);
        $articleCategory->update(request()->all());
        return response()->json(['data' => $articleCategory]);
    }

    public function destroy(ArticleCategory $articleCategory)
    {
        $articleCategory->delete();
        return response()->json(['message' => 'Success']);
    }
}
