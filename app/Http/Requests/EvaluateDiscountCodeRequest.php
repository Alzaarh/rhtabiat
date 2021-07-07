<?php

namespace App\Http\Requests;

use App\Models\DiscountCode;
use Illuminate\Foundation\Http\FormRequest;

class EvaluateDiscountCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(DiscountCode $code)
    {
        return [
            'discount_code' => [
                'required',
                $code->validate(),
            ],
            'order_cost' => 'required|integer',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'discount_code' => 'کد تخفیف',
            'order_cost' => 'مبلغ خرید',
        ];
    }
}
