<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreOrderRequest extends FormRequest
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
            'company' => 'string',
        ];
    }
}
