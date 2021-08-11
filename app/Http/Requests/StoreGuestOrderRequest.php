<?php

namespace App\Http\Requests;

use App\Models\DiscountCode;
use App\Models\ProductItem;
use App\Models\PromoCode;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuestOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'mobile' => ['required', 'digits:11'],
            'phone' => ['digits:11'],
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'zipcode' => ['required', 'digits:10'],
            'address' => ['required'],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:product_items'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'promo_code' => [
                'string',
                function ($fieldName, $fieldValue, $fail) {
                    $promoCode = PromoCode::where('code', $fieldValue)
                        ->where('user_only', false)
                        ->first();

                    if (!$promoCode || $promoCode->isExpired()) {
                        $fail('کد تخفیف معتبر نیست');
                    }

                    $this->merge(['promo_code' => $promoCode]);
                },
            ],
            'discount_code' => [
                function ($attr, $value, $fail) {
                    $code = DiscountCode::where('code', $value)
                        ->whereNull('used_at')
                        ->whereNull('user_id')
                        ->first();
                    if (!$code) {
                        return $fail('کد تخفیف معتبر نیست');
                    }
                    $price = array_reduce(
                        $this->products,
                        fn($c, $i) => ProductItem::find($i['id'])->price * (100 - ProductItem::find($i['id'])->off) / 100 * $i['quantity'] + $c, 0
                    );
                    if ($code->min && $code->min > $price) {
                        $fail('برای اعمال کد تخفیف باید حداقل ' . $code->min . ' خرید کنید');
                    }
                    $this->merge(['discount_code' => $code]);
                }
            ],
        ];
    }

    public function attributes()
    {
        return [
            'mobile' => 'شماره همراه',
            'promo_code' => 'کد تخفیف',
        ];
    }
}
