<?php

namespace App\Http\Requests;

use App\Models\PromoCode;
use App\Rules\AvailablePromoCode;
use App\Rules\CanUsePromoCode;
use App\Rules\CheckMinPromoCode;
use App\Rules\ValidPromoCode;
use Illuminate\Foundation\Http\FormRequest;

class EvaluatePromoCodeRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules()
    {
        return [
            'order_cost' => 'required|integer|min:1',
            'promo_code' => [
                'bail',
                'required',
                'exists:promo_codes,code',
                new CanUsePromoCode,
                new ValidPromoCode,
                new CheckMinPromoCode((int) $this->order_cost),
                new AvailablePromoCode,
            ],
        ];
    }

    public function attributes()
    {
        return [
            'promo_code' => 'کد تخفیف',
            'order_cost' => 'مبلغ خرید',
        ];
    }

    public function promoCodeDiscount(): int
    {
        $promoCode = PromoCode::whereCode($this->promo_code)->first();

        $discount = $promoCode->off_percent
            ? $promoCode->off_percent * $this->order_cost / 100
            : $promoCode->off_value;

        if ($promoCode->max && $discount > $promoCode->max) {
            $discount = $promoCode->max;
        }

        return $discount;
    }
}
