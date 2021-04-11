<?php

namespace App\Http\Requests;

use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;

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
     */
    public function rules()
    {
        return [
            'address' => 'required_without:address_id',
            'address.name' => 'required|max:255',
            'address.company' => 'max:255',
            'address.mobile' => 'required|digits:11',
            'address.phone' => 'digits:11',
            'address.province_id' => 'required|exists:provinces,id',
            'address.city_id' => 'required|exists:cities,id',
            'address.zipcode' => 'required|digits:10',
            'address.address' => 'required|max:2000',
            'products' => 'required_without:address_id|array',
            'products.*.id' => 'required|exists:product_items,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
