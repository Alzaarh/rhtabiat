<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\SaveUserDetailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function register(RegisterUserRequest $request)
    {
        // Send SMS --- fix later
        return response()->json(
            ['data' => $this->vcode->newRegister($request->validated())]
        );
    }

    public function verifyRegister(RegisterUserRequest $request)
    {
        if ($this->vcode->verifyRegister($request->validated())) {
            return response()->json(
                ['data' => ['token' => $this->user->newUser($request->phone)]]
            );
        } else {
            throw ValidationException::withMessages([]);
        }
    }

    public function login(LoginUserRequest $request)
    {
        if ($request->has('phone')) {
            return response()->json(
                $this->vcode->newLogin($request->validated())
            );
        }
        // Add login with email --- fix later
        // Send SMS --- fix later
    }

    public function verifyLogin(LoginUserRequest $request)
    {
        if (!$this->vcode->verifyLogin($request->validated())) {
            throw ValidationException::withMessages([]);
        }
        return response()->json(['data' => [
            'token' => auth()->login(
                $this->user->findWithPhone($request->phone)
            ),
        ],
        ]);
    }

    public function getSelf()
    {
        return new UserResource(auth()->user());
    }

    public function updateSelf(SaveUserDetailRequest $request)
    {
        $request->user()->updateSelf($request->validated());
        return new UserResource($request->user()->refresh());
    }
}
