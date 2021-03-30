<?php

namespace App\Http\Requests;

use App\Models\UserDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LoginUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'email' => [
                'required',
                'email',
                function ($attr, $value, $fail) use ($request) {
                    $UserDetail = UserDetail::where('email', $value)->first();
                    $UserDetail ?
                    $request->merge(['userDetail' => $UserDetail]) :
                    $fail(__('validation.login'));
                }],
            'password' => 'required',
        ];
    }
}
