<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    public function run()
    {
        ArticleCategory::factory()
            ->has(Article::factory()->count(10))
            ->count(5)
            ->create();
    }
}
