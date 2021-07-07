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
                ->latest()->paginate()
        );
    }

    /**
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => __('messages.resource.destroy', ['resource' => 'کامنت']),
        ]);
    }
}
