<?php

namespace App\Http\Controllers\Auth;

use App\Models\VerificationCode;
use Illuminate\Http\Request;

class VerifyForgetPasswordController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            "phone" => [
                "required",
                "digits:11",
            ],
            "code" => [
                "required",
                "digits:5",
            ],
        ]);
        $vcode = VerificationCode::wherePhone($request->input("phone"))
            ->whereCode($request->input("code"))
            ->whereUsage(VerificationCode::USAGES["forget"])
            ->first();
        if (!$vcode) {
            return response()->json([
                "message" => "bad request",
                "errors" => [
                    "phone" => "phone is invalid",
                ],
            ], 400);
        }
        $vcode->usage = VerificationCode::USAGES["change_pass"];
        $vcode->save();
        return response()->json([
            "message" => "success",
        ]);
    }
}
