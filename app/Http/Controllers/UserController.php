<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserDetailRequest;
use Illuminate\Validation\ValidationException;
use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    private $vcode;

    private $user;

    public function __construct(VerificationCode $vcode, User $user)
    {
        $this->middleware('throttle:1,1')->only(['register']);
        $this->middleware('auth')->only(['getSelf']);
        $this->vcode = $vcode;
        $this->user = $user;
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
            ]
        ]);
    }

    public function getSelf()
    {
        auth()->user()->load('detail');
        return new UserResource(auth()->user());
    }

    public function updateSelf(StoreUserDetailRequest $request)
    {
        auth()->user()->newDetail($request->validated());
        return new UserResource(auth()->user()->refresh());
    }
}