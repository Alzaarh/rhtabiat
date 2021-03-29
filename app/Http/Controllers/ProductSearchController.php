<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['term' => 'required|string|max:100']);
        return ProductResource::collection(
            Product::search($request->term)->get()
        );
    }
}
