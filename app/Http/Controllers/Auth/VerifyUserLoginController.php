<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\VerifyUserLoginRequest;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;

class VerifyUserLoginController
{
    public function __invoke(VerifyUserLoginRequest $request)
    {
        $pass = $request->getPassword();
        if ($pass) {
            if (Hash::check($request->input("password"), $pass)) {
                return response()->json([
                    "message" => "success",
                    "data" => ["token" => auth("user")->login($request->getUser())],
                ]);
            }
            return response()->json([
                "message" => "bad request",
                "errors" => ["password" => "اطلاعات وارد شده درست نیست"],
            ], 400);
        }
        $exists = VerificationCode::wherePhone($request->getUser()->phone)
            ->whereUsage(VerificationCode::USAGES["login"])
            ->whereCode($request->input("code"))
            ->exists();
        if ($exists) {
            return response()->json([
                "message" => "success",
                "data" => ["token" => auth("user")->login($request->getUser())],
            ]);
        }
        return response()->json([
            "message" => "bad request",
            "errors" => ["code" => "کد اشتباه است"],
        ], 400);
    }
}
