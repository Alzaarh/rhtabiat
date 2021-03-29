<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = ['phone' => ['required', validPhone(), 'unique:users']];
        if (Route::currentRouteName() === 'register.verify')
            $rules['code'] = 'required|digits:5';
        return $rules;
    }
}
