<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountCodeRequest;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function store(StoreDiscountCodeRequest $request)
    {
        return response()->json([
            'data' => DiscountCode::create($request->validated()),
        ], 201);
    }
}
