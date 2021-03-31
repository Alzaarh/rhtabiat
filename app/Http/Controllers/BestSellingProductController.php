<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class BestSellingProductController extends Controller
{
    public function __invoke(Request $request, Product $product)
    {
        $request->validate(['count' => 'integer|between:1,15']);
        return ProductResource::collection($product->getBestSelling($request->count));
    }
}
