<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    private User $user;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "input" => [
                "required",
                function ($attr, $value, $fail) {
                    $user = User::wherePhone($value)->first();
                    if (!$user) {
                        $userDetail = UserDetail::whereEmail($value)->first();
                        if (!$userDetail) {
                            $fail("user does not exist");
                            return;
                        } else {
                            $user = $userDetail->user;
                        }
                    }
                    if (!$user->detail->password) {
                        $fail("user has no password");
                        return;
                    }
                    $this->user = $user;
                },
            ],
        ];
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
