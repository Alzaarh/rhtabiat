<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;

class AdminLoginController extends Controller
{
    /**
     * Log admin in.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(AuthService $authService)
    {
        request()->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        return response()->json([
            'data' => [
                'token' => $authService->logAdminIn(request()->only(['username', 'password']))
            ],
        ]);
    }
}
