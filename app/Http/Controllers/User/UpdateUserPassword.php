<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserDetail;

class UpdateUserPassword extends Controller
{
    public function __invoke()
    {
        request()->validate(
            [
                'password' => 'string|between:6,30',
                'new_password' => 'string|between:6,30',
            ]
        );

        $user = request()->user();
        $badReq = false;

        if (request()->missing('new_password')) {
            if (!$user->detail) {
                $user->detail()->create(["password" => request()->input('password')]);
            } else {
                $user->detail->password = request()->input('password');
            }
        } else {
            $user->canUpdatePassword(request()->input('password'))
                ? $user->detail->password = request()->input('new_password')
                : $badReq = true;
        }

        if ($badReq) {
            return response()->json(['message' => 'اطلاعات وارد شده صحیح نیست'], 400);
        }

        $user->detail->save();

        return response()->json(['message' => 'رمز عبور با موفقیت تغییر کرد']);
    }
}
