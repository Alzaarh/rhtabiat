<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function handleUserRegister() : User
    {
        $code = VerificationCode::where('phone', request()->phone)
            ->where('code', request()->code)
            ->latest()
            ->first();

        if (empty($code) || now()->diffInMinutes($code->created_at) > 60) {
            throw ValidationException::withMessages([
                'phone' => 'Invalid phone',
            ]);
        }
        
        return DB::transaction(function () {
            $user = User::create(['phone' => request()->phone]);
            $user->cart()->save(new Cart);
            return $user;
        });
    }
}
