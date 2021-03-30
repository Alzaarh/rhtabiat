<?php

namespace Database\Factories;

use App\Models\ProductFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFeatureFactory extends Factory
{
    protected $model = ProductFeature::class;

    public function definition()
    {
        $rand = $this->faker->randomElement([0.25, 0.5, 1, 2, 5, 10, 20]);
        return [
            'weight' => $rand,
            'price' => $rand * 10000,
            'quantity' => $this->faker->randomElement([10, 15, 20]),
        ];
    }
}
