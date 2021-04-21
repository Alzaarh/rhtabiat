<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:11',
            'email' => 'required|email|max:255',
            'message' => 'required|max:10000',
        ];
    }

    public function attributes()
    {
        return [
            'phone' => 'شماره تماس',
        ];
    }
}
