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
        request()->validate(['name' => 'required|max:255|unique:article_categories']);

        ArticleCategory::create(['name' => request()->name]);
        return response()->json([
            'message' => 'دسته بندی با موفقیت ایجاد شد',
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

    public function destroy(ArticleCategory $category)
    {
        $articleCategory->delete();
        return response()->json(['message' => 'دسته بندی با موفقیت حذف شد']);
    }
}
