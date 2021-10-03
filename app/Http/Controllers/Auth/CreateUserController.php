<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateUserController
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
        function ($attr, $value, $fail) use ($request) {
          $exists = VerificationCode::wherePhone($request->input("phone"))
            ->whereUsage(VerificationCode::USAGES["register"])
            ->whereCode($value)
            ->where("created_at", ">=", now()->subHour())
            ->exists();
          if (!$exists) {
            $fail(__("messages.user.invalid_register"));
          }
        },
      ]
    ]);
    $user = User::create([
      "phone" => $request->input("phone"),
    ]);
    $user->cart()->save(new Cart);
    return response()->json([
      "message" => "created",
      "data" =>  ["token" => auth("user")->login($user)],
    ]);
  }
}