<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;

class ChangePasswordController
{
    public function __invoke(Request $request)
    {
        $exists = VerificationCode::wherePhone($request->input("phone"))
            ->whereCode($request->input("code"))
            ->whereUsage(VerificationCode::USAGES["change_pass"])
            ->exists();
        if (!$exists) {
            return response()->json([
                "message" => "bad request",
                "errors" => [
                    "phone" => "invalid phone",
                ],
            ]);
        }
        $user = User::wherePhone($request->input("phone"))->first();
        $user->detail->password = $request->input("password");
        $user->detail->save();
        return response()->json([
            "message" => "success",
        ]);
    }
}
