<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::all()
            ->each(
                fn($article) => Comment::factory()
                    ->for($article, 'commentable')
                    ->count(5)
                    ->create()
            );

        Product::all()
            ->each(
                fn($product) => Comment::factory()
                    ->for($product, 'commentable')
                    ->count(5)
                    ->create()
            );
    }
}
