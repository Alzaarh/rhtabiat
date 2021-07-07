<?php

namespace Database\Factories;

use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleCategoryFactory extends Factory
{
    protected $model = ArticleCategory::class;

    public function definition()
    {
        return [
            'name' => \Faker::word().' '.rand(1, 50),
        ];
    }
}
