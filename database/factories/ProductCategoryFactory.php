<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition()
    {
        return [
            'name' => \Faker::word().' '.rand(1, 50),

            'image' => 'images/product-category.jpg',
        ];
    }
}
