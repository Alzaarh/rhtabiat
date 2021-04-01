<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;

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
        $products->slice(0, 4)->each(fn ($product) => ProductItem::factory(1)->for($product)->create());
        $products->slice(3, 4)->each(fn ($product) => ProductItem::factory(6)->for($product)->create());
        $products->slice(7)->each(fn ($product) => ProductItem::factory(6)->for($product)->create([
            'container' => ProductItem::ZINK_CONTAINER,
        ]));
        $products->slice(7)->each(fn ($product) => ProductItem::factory(6)->for($product)->create([
            'container' => ProductItem::PLASTIC_CONTAINER,
        ]));
    }
}
