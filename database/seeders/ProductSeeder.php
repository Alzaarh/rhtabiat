<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        ProductCategory::doesntHave('children')
            ->get()
            ->each(fn($category) => Product::factory()
                ->for($category, 'category')
                ->count(50)
                ->create());
    }
}
