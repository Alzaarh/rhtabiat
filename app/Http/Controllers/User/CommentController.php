<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $request->resource
            ->comments()
            ->create($request->validated());

        return response()->json(['message' => __('messages.register')], 201);
    }
}
