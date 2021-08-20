<?php

namespace App\Http\Requests;

use App\Models\ProductItem;
use App\Models\PromoCode;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuestOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:11',
            'phone' => 'digits:11',
            'company' => 'string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'zipcode' => 'required|digits:10',
            'address' => 'required|string|max:1000',
            'products' => 'required|array',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.id' => [
                'required',
                'distinct',
                function ($attribute, $value, $fail) {
                    $foundItem = ProductItem::find($value);

                    if (!$foundItem) {
                        $fail();
                    } else {
                        foreach ($this->input('products') as $item) {
                            if ($item['id'] === $value && $item['quantity'] > $foundItem->quantity) {
                                $fail(__('messages.product.invalidQuantity'));
                            }
                        }
                    }
                },
            ],
            'promoCode' => [
                'string',
                function ($attribute, $value, $fail) {
                    $promoCode = PromoCode::where('code', $value)
                        ->where('user_only', false)
                        ->first();

                    if (!$promoCode || $promoCode->isExpired()) {
                        $fail('کد تخفیف معتبر نیست');
                    }
                },
            ],
        ];
    }
}
