<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountCodeRequest;
use App\Services\DiscountCodeService;

class DiscountCodeController extends Controller
{
    protected $discountCodeService;

    public function __construct(DiscountCodeService $discountCodeService)
    {
        $this->discountCodeService = $discountCodeService;
    }
    
    public function store(StoreDiscountCodeRequest $request)
    {
        $this->discountCodeService->handleNewBatch($request->validated());

        return response()->json(['message' => 'Created'], 201);
    }
}
