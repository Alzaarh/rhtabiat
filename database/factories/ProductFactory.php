<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'short_desc' => $this->faker->sentence(15),
            'desc' => $this->faker->paragraph(10),
            'icon' => 'images/product.jpg',
            'category_id' => Category::inRandomOrder()->value('id'),
            'off' => rand(1, 100) > 50 ? rand(5, 50) : null,
        ];
    }
}
