<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;

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

        return response()->json(['data' => [
            'token' => auth('user')->login(
                $this->userService->handleUserRegister()
            )],
        ], 201);
    }

    public function index()
    {
        return UserResource::collection(User::latest()->paginate(10));
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }
}
