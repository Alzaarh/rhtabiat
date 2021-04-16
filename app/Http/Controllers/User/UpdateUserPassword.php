<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UpdateUserPassword extends Controller
{
    /**
     * Handle the incoming request.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        request()->validate(
            [
                'password' => 'required|string|between:6,30',
                'new_password' => 'string|between:6,30',
            ]
        );

        $user = request()->user();
        $badReq = false;

        if (request()->missing('new_password')) {
            $user->canStorePassword() ? $user->detail->password = request()->input('password') : $badReq = true;
        } else {
            $user->canUpdatePassword(request()->input('password'))
                ? $user->detail->password = request()->input('new_password')
                : $badReq = true;
        }

        if ($badReq) {
            return response()->json(['message' => 'Bad request'], 400);
        }

        $user->detail->save();

        return response()->json(['message' => 'Success']);
    }
}
