<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'receiver_name' => 'required|string|max:255',
            'receiver_company' => 'string|max:255',
            'receiver_mobile' => ['required', 'string', validPhone()],
            'receiver_phone' => 'string|digits:11',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zipcode' => 'required|string|digits:10',
            'address' => 'required|string|max:1000',
        ];
        [
            'products' => 'required|array',
            'products.*.id' => 'required|exists:product_features,id',
            'products.*.quantity' => [''],
        ];
    }
}
