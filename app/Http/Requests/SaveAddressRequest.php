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
            'name' => 'required|string|max:255',
            'company' => 'string|max:255',
            'mobile' => ['required', 'string', validPhone()],
            'phone' => 'string|digits:11',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zipcode' => 'required|string|digits:10',
            'address' => 'required|string|max:1000',
        ];
    }
}
