<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginUserRequest;
use App\Models\VerificationCode;
use App\Jobs\NotifyViaSms;
use App\Models\User;
use App\Models\UserDetail;

class LoginUserController
{
  public function __invoke(LoginUserRequest $request)
  {
    $hasPass = $request->hasPassword();
    if ($hasPass) {
      return response()->json([
        "message" => "success",
        "data" => ["has_password" => $hasPass],
      ]);
    }
    $code = rand(10000, 99999);
    $phone = User::wherePhone($request->input('input'))->value('phone');
    $detail = UserDetail::whereEmail($request->input('input'))->first();
    VerificationCode::create([
      "code" => $code,
      "usage" => VerificationCode::USAGES["login"],
      "phone" => $phone ?? $detail->user->phone,
    ]);
    NotifyViaSms::dispatch($phone ?? $detail->user->phone, config("app.sms_patterns.verification"), ["code" => $code]);
    return response()->json([
      "message" => "success",
      "data" => [
        "has_password" => $hasPass,
      ],
    ]);
  }
}