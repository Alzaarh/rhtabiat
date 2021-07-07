<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => \Faker::word().' '. rand(1, 10000),

            'short_desc' => $this->faker->realText(500),

            'desc' => $this->faker->realText(2000),

            'image' => $this->faker->randomElement([
                'images/product-1.png',
                'images/product-2.png',
            ]),

            'off' => rand(1, 100) > 50 ? rand(5, 50) : 0,
        ];
    }
}
