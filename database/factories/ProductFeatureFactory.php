<?php

namespace Database\Factories;

use App\Models\ProductFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFeatureFactory extends Factory
{
    protected $model = ProductFeature::class;

    public function definition()
    {
        $rand = rand(1, 10);
        return [
            'weight' => $rand,
            'price' => $rand * 10000,
            'quantity' => rand(5, 20),
        ];
    }
}
