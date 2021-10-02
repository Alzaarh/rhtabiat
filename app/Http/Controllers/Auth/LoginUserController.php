<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginUserRequest;

class LoginUserController
{
  public function __invoke(LoginUserRequest $request)
  {
    return response()->json([
      "message" => "success",
      "data" => ["has_password" => $request->hasPassword()],
    ]);
  }
}