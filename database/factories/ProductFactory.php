<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => $this->faker->randomElement(['روغن گوسفندی', 'عسل', 'کشک', 'شیر', 'ماست', 'کره گوسفندی']),
            'slug' => $this->faker->unique()->word,
            'short_desc' => \Faker\Factory::create('fa_IR')->realText(100),
            'desc' => \Faker\Factory::create('fa_IR')->realText(1000),
            'meta_tags' => json_encode([
                'description' => $this->faker->realText(),
                'keywords' => $this->faker->sentence(10),
            ]),
            'image' => $this->faker->randomElement(['images/product-1.png', 'images/product-2.png']),
            'off' => rand(1, 100) > 50 ? rand(5, 50) : 0,
            'category_id' => ProductCategory::inRandomOrder()->value('id'),
        ];
    }
}
