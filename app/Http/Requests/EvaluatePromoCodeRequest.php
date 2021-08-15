<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluatePromoCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'promo_code' => 'required|string|exists:promo_codes,code',
            'order_cost' => 'required|integer|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'promo_code' => 'کد تخفیف',
            'order_cost' => 'مبلغ خرید',
        ];
    }
}
