<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{
    public function __invoke(LoginUserRequest $request)
    {
        if (!Hash::check($request->password, $request->userDetail->password)) {
            throw ValidationException::withMessages([
                'email' => __('validation.login'),
            ]);
        }
        return jsonResponse(['data' => [
            'token' => auth()->login($request->userDetail->user)],
        ]);
    }
}
