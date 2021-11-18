<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePromoCodeRequest;
use App\Http\Requests\UpdatePromoCodeRequest;
use App\Models\PromoCode;

class PromoCodeController extends Controller
{
    public function index()
    {
        return response()->json(['data' => PromoCode::paginate(10)]);
    }

    public function store(StorePromoCodeRequest $request)
    {
        PromoCode::create($request->validated());

        return response()->json(['message' => __('messages.promo_code.store')], 201);
    }

    public function show(PromoCode $promoCode)
    {
        return response()->json(['data' => $promoCode]);
    }

    public function update(UpdatePromoCodeRequest $request, PromoCode $promoCode)
    {
        $promoCode->update($request->validated());
        return response()->json(['message' => __('messages.promo_code.update')]);
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return response()->json([
            'message' => __('messages.promo_code.destroy'),
        ]);
    }
}
