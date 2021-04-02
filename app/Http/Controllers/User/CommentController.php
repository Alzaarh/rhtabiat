<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $request->resource
            ->comments()
            ->save(new Comment($request->validated()));
        return response()->json(['message' => 'Success'], 201);
    }
}
