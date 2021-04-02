<?php

namespace Database\Factories;

use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleCategoryFactory extends Factory
{
    protected $model = ArticleCategory::class;

    public function definition()
    {
        $name = $this->faker->unique()->sentence(3);
        return [
            'name' => $name,
            'slug' => $name,
        ];
    }
}
