<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePromoCodeRequest;
use App\Models\PromoCode;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => PromoCode::paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePromoCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePromoCodeRequest $request)
    {
        PromoCode::create($request->validated());

        return response()->json(['message' => __('messages.promo_code.store')], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function show(PromoCode $promoCode)
    {
        return response()->json(['data' => $promoCode]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        $request->validate(['count' => [
            'required',
            'integer', 
            'min:' . $promoCode->count,
        ]]);

        $promoCode->count = $request->input('count');
        $promoCode->save();

        return response()->json(['message' => __('messages.promo_code.update')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return response()->json([
            'message' => __('messages.promo_code.destroy'),
        ]);
    }
}
