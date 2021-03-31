<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SaveUserDetailRequest extends FormRequest
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
                Rule::unique('user_details')->ignore(auth()->user()->detail),
            ],
            'phone' => [
                'required',
                validPhone(),
                Rule::unique('users')->ignore(auth()->user()),
            ],
            'password' => [
                'required',
                'string',
                'between:6,30',
                function ($attr, $value, $fail) use ($request) {
                    if (!Hash::check($value, $request->user()->detail->password)) {
                        $fail(__('validation.custom'));
                    }
                }],
            'new_password' => 'string|between:6,30',
        ];
    }
}
