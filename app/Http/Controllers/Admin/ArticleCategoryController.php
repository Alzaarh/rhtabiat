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

    public function update(ArticleCategory $category)
    {
        request()->validate(['name' => [
            'required',
            'max:255',
            Rule::unique('article_categories')->ignore($category)
        ]]);

        $category->update(['name' => request()->name]);
        return response()->json(['message' => 'دسته بندی با موفقیت به روزرسانی شد']);
    }

    public function destroy(ArticleCategory $category)
    {
        $category->delete();
        return response()->json(['message' => 'دسته بندی با موفقیت حذف شد']);
    }
}
