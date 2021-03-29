<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'role:boss'])->only([
            'store',
            'update',
            'destroy'
        ]);
    }

    public function index()
    {
        return response()->json([
            'data' => BlogCategory::orderBy('id', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255|unique:blog_categories']);
        $blogCategory = BlogCategory::create(['title' => $request->title]);
        return response()->json(['data' => $blogCategory], 201);
    }

    public function show(BlogCategory $category)
    {
        return response()->json(['data' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogCategory $category)
    {
        $request->validate(['title' => [
            'required',
            'string',
            'max:255',
            function ($attr, $value, $fail) use ($category) {
                if (BlogCategory::where('title', $value)->where('id', '!=', $category->id)->exists()) {
                    $fail('Invalid title');
                }
            },
        ]]);
        $category->update(['title' => $request->title]);
        return response()->json(['data' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogCategory $category)
    {
        $category->delete();
        return response()->json(['data' => $category]);
    }
}
