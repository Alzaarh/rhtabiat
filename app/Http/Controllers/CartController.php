<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        return new CartResource(auth()->user()->cart);
    }
}
