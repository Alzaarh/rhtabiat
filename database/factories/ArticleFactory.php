<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(6),
            'thumbnail' => 'images/article.jpg',
            'body' => $this->faker->randomHtml(3, 4),
            'meta' => collect([
                'keywords' => ['keyword1', 'keyword2', 'keyword3', 'keyword4'],
                'description' => 'some description',
            ])->toJson(),
            'admin_username' => Admin::hasRole(Admin::ROLES['writer'])
                ->inRandomOrder()
                ->value('username'),
        ];
    }
}
