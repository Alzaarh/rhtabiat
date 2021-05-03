<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        Article::take(10)
            ->get()
            ->each(fn($article) =>
                Comment::factory()
                    ->for($article, 'commentable')
                    ->count(5)
                    ->create());

        Product::inRandomOrder()
            ->take(20)
            ->get()
            ->each(fn($product) => Comment::factory()
                ->for($product, 'commentable')
                ->count(5)
                ->create());
    }
}
