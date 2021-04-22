<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
//        $faFaker = Faker::create('fa_IR');

        return [
            'title' => substr(
                $this->faker->unique()->realText(200, 4),
                0,
                30
            ),

            'image' => 'images/article.jpg',

            'body' => $this->faker->realText(2000),

            'is_verified' => rand(1, 100) > 50,

            'admin_id' => 1,
        ];
    }
}
