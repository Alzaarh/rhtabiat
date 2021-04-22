<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
//        Article::all()
//            ->each(fn($article) =>
//                Comment::factory()
//                    ->for($article, 'commentable')
//                    ->count(5)
//                    ->make()
//                    ->each(function ($article) use ($comments) {
//                        array_push($comments, $article);
//                    }));

        Product::inRandomOrder()
            ->take(50)
            ->get()
            ->each(fn($product) => Comment::factory()
                ->for($product, 'commentable')
                ->count(5)
                ->create());
    }
}
