<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryHierarchyController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->merge(['withChildren' => true]);
        return CategoryResource::collection(Category::getHierarchy());
    }
}
