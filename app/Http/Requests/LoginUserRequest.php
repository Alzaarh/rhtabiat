<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LoginUserRequest extends FormRequest
{
  private bool $hasPassword;

  public function authorize()
  {
    return true;
  }

  public function rules(Request $request)
  {
    return [
      "input" => [
        "required",
        function ($attr, $value, $fail) {
          $user = User::wherePhone($value)->first();
          if ($user) {
          $this->hasPassword = (bool) $user->detail;
          } else {
            $user = UserDetail::whereEmail($value)->first();
            if (!$user) {
              $fail("اطلاعات وارد شده صحیح نیست");
            } else {
              $this->hasPassword = (bool) $user->password;
            }
          }
        },
      ],
    ];
  }

  public function hasPassword()
  {
    return $this->hasPassword;
  }
}
