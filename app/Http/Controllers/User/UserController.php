<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store()
    {
        request()->validate([
            'phone' => 'required|unique:users',
            'code' => 'required',
        ]);

        $user = $this->userService->handleUserRegister();

        return response()->json([
            'data' => [
                'token' => auth('user')->login($user),
            ]
        ], 201);
    }
}
