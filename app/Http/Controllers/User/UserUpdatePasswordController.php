<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserUpdatePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        request()->validate([
            'password' => 'required|string|between:6,30',
            'new_password' => 'string|between:6,30',
        ]);

        $user = request()->user();
        $badReq = false;

        if (request()->missing('new_password')) {
            $user->canStorePassword()
                ? $user->detail->password = request()->password
                : $badReq = true;
        } else {
            $user->canUpdatePassword(request()->password)
                ? $user->detail->password = request()->new_password
                : $badReq = true;
        }
        
        if ($badReq) {
            return response()->json(['message' => 'Bad request'], 400);
        }
        
        $user->detail->save();

        return response()->json(['message' => 'Success']);
    }
}
