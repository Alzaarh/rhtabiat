<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_details')->ignore(auth()->user()->detail),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user_details')->ignore(auth()->user()->detail),
            ],
            'phone' => [
                'required',
                'string',
                validPhone(),
                Rule::unique('users')->ignore(auth()->user()),
            ],
            'password' => 'required|string|min:6|max:30',
        ];
        if (auth()->user()->hasDetail()) {
            unset($rules['password']);
            $rules['newPassword'] = 'string|min:6|max:30';
            $rules['oldPassword'] = 'required_with:newPassword|string|max:30';
        }
        return $rules;
    }
}
