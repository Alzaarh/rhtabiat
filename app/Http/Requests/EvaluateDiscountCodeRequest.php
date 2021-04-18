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
    public function rules()
    {
        return [
            'discount_code' => [
                'required',
                'exists:discount_codes,code',
                function ($attribute, $value, $fail) {
                    $code = DiscountCode::whereCode($value)->notUsed()->first();
                    if (
                        empty($code) ||
                        (
                            filled($code->user_id) &&
                            $code->user_id !== auth('user')->id()
                        )
                    ) {
                        $fail('کد تخفیف معتبر نیست');
                    }
                }
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
