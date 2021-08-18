<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:11',
            'order_code' => 'required|exists:orders,code',
            'email' => 'string|email|max:255',
            'reason' => 'required|string|max:64000',
        ];
    }
}
