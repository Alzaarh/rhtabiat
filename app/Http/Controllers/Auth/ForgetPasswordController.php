<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ForgetPasswordRequest;
use App\Models\VerificationCode;

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
        return response()->json([
            "message" => "success",
            "data" => [
                "code" => $code,
            ],
        ]);
    }
}
