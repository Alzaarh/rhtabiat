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
        $products->slice(3, 4)->each(fn ($product) => ProductItem::factory(3)->for($product)->create());
        $products->slice(7)->each(fn ($product) => ProductItem::factory(4)->for($product)->create([
            'container' => 'zink',
        ]));

        $products->slice(7)->each(fn ($product) => ProductItem::factory(4)->for($product)->create([
            'container' => 'plastic',
        ]));
    }
}
