<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Jobs\NotifyViaSms;

class RegisterUserController
{
  public function __invoke(Request $request)
  {
    $request->validate([
      "phone" => [
        "required",
        "digits:11",
        "unique:users",
      ]
    ]);
    $code = rand(10000, 99999);
    VerificationCode::create([
      "phone" => $request->input("phone"),
      "usage" => VerificationCode::USAGES["register"],
      "code" => $code
    ]);
    NotifyViaSms::dispatch($request->input("phone"), config("app.sms_patterns.verification"), ["code" => $code]);
    return response()->json(["message" => __("messages.user.register")]);
  }
}