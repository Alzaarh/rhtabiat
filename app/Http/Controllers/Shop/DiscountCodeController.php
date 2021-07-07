<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountCodeRequest;
use App\Models\DiscountCode;
use App\Models\DiscountCodeGroup;
use Illuminate\Support\Facades\DB;

class DiscountCodeController extends Controller
{
    protected DiscountCode $discountCode;

    public function __construct(DiscountCode $discountCode)
    {
        $this->discountCode = $discountCode;
    }

    public function index()
    {
        return response()->json([
            'data' => DiscountCode::groupBy('group_id')->paginate(request()->query('count', 10))
        ]);
    }

    public function store(StoreDiscountCodeRequest $request)
    {
        DB::transaction(function () use ($request) {
           $group = DiscountCodeGroup::create([
               'min' => $request->min,
               'max' => $request->max,
               'percent' => $request->percent,
               'value' => $request->value,
               'expires_at' => $request->expires_at,
           ]);
            $codes = [];
            for ($i = 0; $i < $request->count; $i++) {
                array_push($codes, [
                    'code' => $request->has('code') ? $request->code : $this->discountCode->generateCode(),
                ]);
            }
           $group->discountCodes()->createMany($codes);
        });
        return response()->json(['message' => 'کد تخفیف با موفقیت ایجاد شد'], 201);
    }
}
