<?php

namespace App\Http\Requests;

use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;

class GuestOrder extends FormRequest
{
    public function rules()
    {
        return array_merge(Address::RULES, [
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:product_items'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ]);
    }

    protected function prepareForValidation()
    {
        $this->merge(['province_id' => $this->province]);
        $this->merge(['city_id' => $this->city]);
    }
}
