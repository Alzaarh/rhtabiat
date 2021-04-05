<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $request->validate(['count' => 'integer|min:1|max:15']);
        // $comments = $request->whenHas('latest', function () use ($request) {
        //     return Comment::latest()->take($request->query('count', 3))->get();
        // });
        // return CommentResource::collection($comments);
        return CommentResource::collection(Comment::paginate());
    }

    public function update(Comment $comment)
    {
        $comment->status = request()->status;
        $comment->update();
        return new CommentResource($comment);
    }
}
