<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => rand(1, 1000) . ' ' . \Faker::sentence() . ' ' . rand(1, 1000),
            'image' => $this->faker->randomElement([
                'images/article.jpg',
                'images/article-2.jpg',
            ]),
            'body' => $this->faker->realText(2000),
            'is_verified' => true,
            'admin_id' => 1,
        ];
    }
}
