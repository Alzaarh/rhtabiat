<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class UpdateCommentTestimonial
{
    public function __invoke(Comment $comment)
    {
        $comment->is_testimonial = !$comment->is_testimonial;
        $comment->save();
        return response()->json(['message' => __('messages.success')]);
    }
}
