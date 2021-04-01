<?php

namespace Database\Factories;

use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $weight = rand(1, 10);
        return [
            'weight' => $weight,
            'price' => $weight * 10000,
            'quantity' => rand(11, 20),
        ];
    }
}
