<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreUserDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user_details')->ignore(auth("user")->user()->detail),
            ],
        ];
    }
}
