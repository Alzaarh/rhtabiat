<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'company' => 'string|max:255',
            'mobile' => 'required|digits:11',
            'phone' => 'digits:11',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'zipcode' => 'required|digits:10',
            'address' => 'required|max:1000',
        ];
    }
}
