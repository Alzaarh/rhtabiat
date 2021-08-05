<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\PromoCode;

class StorePromoCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:255|alpha_num|unique:promo_codes',
            'user_only' => 'required|boolean',
            'one_per_user' => 'required|boolean',
            'off_percent' => 'required_without:off_value|integer|min:1|max:99',
            'off_value' => 'required_without:off_percent|integer|min:1000',
            'max' => 'integer|min:1000',
            'min' => 'integer|min:1000',
            'infinite' => 'required|boolean',
            'count' => 'integer|min:1',
            'valid_days' => 'required|integer|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'کد',
            'user_only' => 'مخصوص کاربر',
            'one_per_user' => 'هر کاربر یک بار',
            'off_percent' => 'درصد تخفیف',
            'off_value' => 'مبلغ تخفیف',
            'max' => 'صقف تخفیف',
            'min' => 'حداقل مبلغ خرید',
            'infinite' => 'بی نهایت',
            'count' => 'تعداد کوپن',
            'valid_days' => 'اعتبار',
        ];
    }
}
