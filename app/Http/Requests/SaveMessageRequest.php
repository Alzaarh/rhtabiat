<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'phone' => 'required',
            'email' => 'required|email|max:255',
            'message' => 'required|max:10000',
        ];
    }
}
