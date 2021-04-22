<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;

class ProductItemSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        $products->slice(0, 10)
            ->each(fn($product) => ProductItem::factory()->for($product)->create());

        $products->slice(10, 20)
            ->each(fn($product) => ProductItem::factory()->count(5)->for($product)->create());

        $products->slice(30)
            ->each(fn($product) => ProductItem::factory()->count(8)->for($product)->create([
                'container' => ProductItem::ZINC_CONTAINER,
            ]));

        $products->slice(30)
            ->each(fn($product) => ProductItem::factory()->count(7)->for($product)->create([
                'container' => ProductItem::PLASTIC_CONTAINER,
            ]));
    }
}
