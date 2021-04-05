<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function store(VerificationCode $verificationCode)
    {
        request()->validate([
            'phone' => 'required|exists:verification_codes',
            'code' => 'required',
        ]);
        if (!$verificationCode->isCodeValid(request()->phone, request()->code)) {
            throw ValidationException::withMessages(['phone' => 'Invalid phone']);
        }
        $user = User::create(['phone' => request()->phone]);
        return response()->json(['data' => ['tokne' => auth('user')->login($user)]], 201);
    }
}
