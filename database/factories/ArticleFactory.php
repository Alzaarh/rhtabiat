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
            'title' => \Faker::word() . ' ' . \Faker::word() . ' ' . \Faker::word() . ' ' . rand(1, 1000),
            'image' => $this->faker->randomElement([
                'images/article.jpg',
                'images/article-2.jpg',
            ]),
            'short_desc' => $this->faker->realText(),
            'body' => $this->faker->realText(5000),
            'is_verified' => true,
            'admin_id' => 1,
        ];
    }
}
