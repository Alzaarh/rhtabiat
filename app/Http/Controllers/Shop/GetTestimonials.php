<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class GetTestimonials extends Controller
{
    public function __invoke()
    {
        return CommentResource::collection(
            Comment::testimonials()->take(request()->count)->get()
        );
    }
}