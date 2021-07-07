<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveCategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->middleware(['auth:admin', 'role:admin'])->except([
            'index', 'show'
        ]);
    }

    public function index(Request $request)
    {
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function store(SaveCategoryRequest $request)
    {
        $data = $request->validated();
        $data['icon'] = $this->categoryService
            ->handleUploadedIcon($request->icon);
        return new CategoryResource(Category::create($data));
    }

    public function update(SaveCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $icon = $this->categoryService->handleUploadedIcon($request->icon);
        filled($icon) ? $data['icon'] = $icon : '';
        $category->update($data);
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return new CategoryResource($category);
    }
}
