<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LoginUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'phone' => [
                'required_without:email',
                'exists:users',
                'string',
                validPhone()
            ],
            'email' => 'email|max:255|exists:user_details',
            'password' => 'required_with:email|string|max:30',
        ];
        if (Route::currentRouteName() === 'login.verify') {
            $rules['code'] = 'required|string|digits:5';
        }
        return $rules;
    }
}
