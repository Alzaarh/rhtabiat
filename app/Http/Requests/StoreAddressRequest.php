<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receiver' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'mobile' => ['required', 'string', validPhone()],
            'phone' => 'required|string|digits:11',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zipcode' => 'required|string|digits:10',
            'address' => 'required|string|max:1000',
        ];
    }
}
