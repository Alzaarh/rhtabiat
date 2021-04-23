<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComment;

class CommentController extends Controller
{
    public function store(StoreComment $request)
    {
        $request->resource
            ->comments()
            ->create($request->validated());

        return response()->json([
            'message' => __('messages.comment.store'),
        ], 201);
    }
}
