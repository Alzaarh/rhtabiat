<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Http\FormRequest;

class VerifyUserLoginRequest extends FormRequest
{
    private ?User $user = null;
    private ?string $password = null;

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
                    $this->user = $user;
                    if ($user && $user->detail) {
                        $this->password = $user->detail->password;
                    }
                    if (!$user) {
                        $user = UserDetail::whereEmail($value)->first();
                        if ($user) {
                            $this->password = $user->password;
                            $this->user = $user->user;
                        }
                    }
                    if (!$user) {
                        $fail();
                    }
                },
            ],
            "password" => [
                "required_without:code",
                "string",
            ],
            "code" => [
                "digits:5",
            ],
        ];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
