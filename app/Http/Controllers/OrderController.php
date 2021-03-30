<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveOrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        return OrderResource::collection(auth()->user()->orders()->paginate());
    }

    public function store(SaveOrderRequest $request)
    {
        $request->validated();
    }
}
