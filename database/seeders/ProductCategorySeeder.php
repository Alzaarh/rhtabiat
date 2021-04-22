<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        ProductCategory::factory()
            ->count(4)
            ->create();

        ProductCategory::factory()
            ->count(4)
            ->has(ProductCategory::factory()->count(2), 'children')
            ->create();
    }
}
