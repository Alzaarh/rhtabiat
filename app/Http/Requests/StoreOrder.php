<?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Models\DiscountCode;
use App\Models\ProductItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrder extends FormRequest
{
    public function rules()
    {
        if (auth('user')->check()) {
            $user = request()->user();
            if (!$user->cart->validate()) {
                abort(400);
            }
            return [
                'address' => [
                    'required',
                    function ($attr, $value, $fail) use ($user) {
                        if ($user->addresses()->whereId($value)->doesntExist()) {
                            $fail();
                        }
                    }
                ],
                'discount_code' => [
                    'exists:discount_codes,code',
                    function ($attr, $value, $fail) {
                        if (!DiscountCode::whereCode($value)->first()->isValid()) {
                            $fail();
                        }
                    },
                ],
            ];
        } else {
            array_merge(Address::RULES, [
                'products' => ['required', 'array'],
                'products.*.id' => 'required|exists:product_items',
                'products.*.quantity' => [
                    'required',
                    'integer',
                    'min:1',
                    function ($attr, $qty, $fail) {

                    }
                ],
            ]);
            $rules['products'] = [
                'required',
                'array',
                function ($attr, $value, $fail) {
                    foreach ($value as $i) {
                        $item = ProductItem::find($i['id']);
                        if (empty($item) || $item->quantity < $i['quantity']) {
                            $fail('تعداد محصول وارد شده بیش از حد مجاز است');
                        }
                    }
                },
            ];
////            'discount_code' => [
////                'bail',
////                'exists:discount_codes,code',
////                function ($attr, $value, $fail) {
////                    $code = DiscountCode::whereCode($value)->first();
////                    if (!$code->isValid()) {
////                        $fail();
////                    }
////                    if (filled($code->min)) {
////                        $price = 0;
////                        foreach ($this->products as $i) {
////                            $item = ProductItem::find($i);
////                            $price += $item->price * (100 - $item->product->off) / 100 * $i['quantity'];
////                        }
////                        if ($code->min > $price) {
////                            $fail();
////                        }
////                    }
////                },
////            ],
//            ];
//        }
        }
    }

    public function attributes()
    {
        return [
            'name' => 'نام تحویل گیرنده',
            'mobile' => 'شماره همراه',
            'phone' => 'شماره تلفن ثابت',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'province_id' => $this->province,
            'city_id' => $this->city,
        ]);
    }
}
