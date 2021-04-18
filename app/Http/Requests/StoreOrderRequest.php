<?php

namespace App\Http\Requests;

use App\Models\ProductItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
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
     * @throws ValidationException
     */
    public function rules()
    {
        if (auth('user')->check()) {
            return [
                'address_id' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!request()->user()->hasAddress($value)) {
                            $fail("{$attribute} is invalid.");
                        }
                    }
                ]
            ];
        }

        foreach (request()->products as $product) {
            $item = ProductItem::find($product['id']);
            if (empty($item) || $item->quantity < $product['quantity']) {
                throw ValidationException::withMessages([
                    'products' => 'تعداد محصول معتبر نیست',
                ]);
            }
        }

        return [
            'name' => 'required|string|max:255',
            'company' => 'string|max:255',
            'mobile' => 'required|digits:11',
            'phone' => 'digits:11',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'zipcode' => 'required|digits:10',
            'address' => 'required|max:2000',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:product_items,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'نام تحویل گیرنده',
            'mobile' => 'شماره همراه',
            'phone' => 'شماره تلفن ثابت',
            'products' => 'محصولات',
            'products.*.id' => 'شناسه محصول',
            'products.*.quantity' => 'تعداد محصول',
        ];
    }
}
