<?php

namespace App\Http\Controllers\User;

use App\Jobs\NotifyViaSms;
use App\Http\Controllers\Controller;
use App\Models\VerificationCode;

class VerificationCodeController extends Controller
{
    public function store()
    {
        request()->validate(['phone' => 'required|digits:11|unique:users']);
        $code = rand(10000, 99999);
        VerificationCode::create([
            'phone' => request()->phone,
            'code' => $code,
            'usage' => VerificationCode::USAGES['register'],
        ]);
        NotifyViaSms::dispatch(request()->phone, config('app.sms_patterns.register'), ['code' => $code]);
        return response()->json(['message' => 'کد تایید برای شما ارسال شد'], 201);
    }
}
