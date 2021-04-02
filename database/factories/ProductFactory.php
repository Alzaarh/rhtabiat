<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->sentence(3);
        return [
            'name' => $name,
            'slug' => $name,
            'short_desc' => $this->faker->sentence(15),
            'desc' => $this->faker->text(1000),
            'image' => 'images/product.jpg',
            'off' => rand(1, 100) > 50 ? rand(5, 50) : 0,
        ];
    }
}
