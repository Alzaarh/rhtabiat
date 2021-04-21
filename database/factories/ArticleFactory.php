<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => \Faker\Factory::create('fa_IR')->unique()->realText(30),
            'image' => 'images/article.jpg',
            'body' => $this->faker->realText(10000),
            'meta' => collect([
                'keywords' => ['keyword1', 'keyword2', 'keyword3', 'keyword4'],
                'description' => $this->faker->sentence(4),
            ])->toJson(),
            'is_verified' => rand(1, 100) > 50,
            'admin_id' => Admin::where('role', Admin::ROLES['writer'])->inRandomOrder()->value('id'),
            'article_category_id' => ArticleCategory::inRandomOrder()->value('id'),
        ];
    }
}
