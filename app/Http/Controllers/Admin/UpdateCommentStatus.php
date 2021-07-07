<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommentStatus as Request;
use App\Models\Comment;

class UpdateCommentStatus extends Controller
{
    public function __invoke(Request $request, Comment $comment)
    {
        if ($request->verify) {
            $comment->verify();
        } else {
            $comment->reject();
        }

        return response()->json(['message' => __('messages.success')]);
    }
}
