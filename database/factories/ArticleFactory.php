<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        $title = $this->faker->unique()->sentence(6);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'thumbnail' => 'images/article.jpg',
            'body' => $this->faker->realText(10000),
            'meta' => collect([
                'keywords' => ['keyword1', 'keyword2', 'keyword3', 'keyword4'],
                'description' => 'some description',
            ])->toJson(),
            'admin_id' => Admin::where('role', Admin::WRITER)->inRandomOrder()->value('id'),
            'article_category_id' => ArticleCategory::inRandomOrder()->value('id'),
        ];
    }
}
