<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserDetailRequest;
use App\Http\Resources\UserDetailResource;
use App\Models\UserDetail;

class UserDetailController extends Controller
{
    public function update(StoreUserDetailRequest $request)
    {
        $user = $request->user();

        empty($user->detail)
            ? $user->detail()->save(new UserDetail($request->validated()))
            : $user->detail->update($request->validated());
            
        return response()->json(['message' => 'Profile updated']);
    }
}
