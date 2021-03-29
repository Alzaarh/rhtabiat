<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['getSelf']);
    }

    public function getSelf()
    {
        auth()->user()->load('detail');
        return new UserResource(auth()->user());
    }

    public function store()
    {

    }
}
