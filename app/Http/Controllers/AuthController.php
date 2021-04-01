<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'exists:user_details',
            'phone' => 'required_without:email|exists:users',
        ]);
        if ($request->has('phone')) {
            DB::table('password_resets')->insert([
                'phone' => $request->phone,
                'token' => rand(10000, 99999),
            ]);
            // Send SMS
        } else {
            // Forget password with email
        }
        return jsonResponse(['message' => 'ok']);
    }
}
