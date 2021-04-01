<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::doesntHave('children')
            ->get()
            ->each(fn ($category) => Product::factory(50)->for($category, 'category')->hasComments(10)->create());
    }
}
