<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;

class VerifyUserLoginController
{
  public function __invoke(Request $request)
  {
    $request->validate([
      // "has_password"
      // "input" => [
      //   "required",
      //   function ($attr, $value, $fail) {
      //     $user = User::wherePhone($value)->first();
      //     if ($user) {

      //     }
      //   }
      ]
    ]);
  }
}