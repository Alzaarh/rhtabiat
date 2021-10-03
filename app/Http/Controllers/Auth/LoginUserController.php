<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginUserRequest;
use App\Models\VerificationCode;

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
    VerificationCode::create([
      "code" => $code,
      "usage" => VerificationCode::USAGES["login"],
      "phone" => $request->input("input"),
    ]);
    return response()->json([
      "message" => "success",
      "data" => [
        "has_password" => $hasPass,
        "code" => $code,
      ],
    ]);
  }
}