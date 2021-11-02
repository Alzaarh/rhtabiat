<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ForgetPasswordRequest;
use App\Models\VerificationCode;
use App\Jobs\NotifyViaSms;

class ForgetPasswordController
{
    public function __invoke(ForgetPasswordRequest $request)
    {
        $code = rand(10000, 99999);
        VerificationCode::create([
            "phone" => $request->getUser()->phone,
            "code" => $code,
            "usage" => VerificationCode::USAGES["forget"],
        ]);
        NotifyViaSms::dispatch($request->input("input"), config("app.sms_patterns.verification"), ["code" => $code]);
        return response()->json([
            "message" => "success",
            "data" => [],
        ]);
    }
}
