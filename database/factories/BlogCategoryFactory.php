<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogCategoryFactory extends Factory
{
    protected $model = BlogCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
