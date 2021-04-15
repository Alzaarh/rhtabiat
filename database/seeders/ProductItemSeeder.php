<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        $products
            ->slice(0, 5)
            ->each(fn ($product) => ProductItem::factory()->for($product)->create());
        $products
            ->slice(5, 5)
            ->each(fn ($product) => ProductItem::factory()->count(5)->for($product)->create());
        $products
            ->slice(10)
            ->each(fn ($product) => ProductItem::factory()->count(8)->for($product)->create([
                'container' => ProductItem::ZINK_CONTAINER,
            ]));
        $products
            ->slice(10)
            ->each(fn ($product) => ProductItem::factory()->count(7)->for($product)->create([
                'container' => ProductItem::PLASTIC_CONTAINER,
            ]));
    }
}
