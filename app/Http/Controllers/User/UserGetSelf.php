<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserGetSelf extends Controller
{
    public function __invoke()
    {
        return new UserResource(request()->user());
    }
}
