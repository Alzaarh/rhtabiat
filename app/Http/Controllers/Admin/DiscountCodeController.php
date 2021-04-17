<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountCodeRequest;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\DB;

class DiscountCodeController extends Controller
{
    protected DiscountCode $discountCode;

    public function __construct(DiscountCode $discountCode)
    {
        $this->discountCode = $discountCode;
    }

    public function store(StoreDiscountCodeRequest $request)
    {
        $codes = [];

        if ($request->has('users')) {
            foreach ($request->input('users') as $user) {
                array_push(
                    $codes,
                    array_merge(
                        $request->only(['min', 'max', 'percent', 'value', 'expires_at']),
                        ['user_id' => $user, 'code' => $this->discountCode->generateCode()]
                    )
                );
            }
        }

        if ($request->has('count')) {
            for ($i = 0; $i < $request->input('count'); $i++) {
                array_push(
                    $codes,
                    array_merge(
                        $request->only(['min', 'max', 'percent', 'value', 'expires_at']),
                        ['code' => $this->discountCode->generateCode()]
                    )
                );
            }
        }
        DB::table('discount_codes')->insert($codes);

        return response()->json(['message' => __('messages.resource.created', ['resource' => 'کد تخفیف'])], 201);
    }
}
