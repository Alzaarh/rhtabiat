<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        return CommentResource::collection(
            Comment::withoutGlobalScope('verified')
                ->latest()
                ->paginate(request()->count)
        );
    }

    public function update(Comment $comment)
    {
        request()->validate(['status' => 'required|in:1,2,3']);
        $comment->status = request()->status;
        $comment->save();
        return new CommentResource($comment);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Success']);
    }
}
