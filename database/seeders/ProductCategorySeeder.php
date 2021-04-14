<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::create([
            'name' => 'روغن حیوانی',
            'slug' => 'روغن-حیوانی',
            'image' => 'images/oil.png',
        ]);
        ProductCategory::create([
            'name' => 'کره حیوانی',
            'slug' => 'کره-حیوانی',
            'image' => 'images/butter.png',
        ]);
        ProductCategory::create([
            'name' => 'متفرقه',
            'slug' => 'متفرقه',
            'image' => 'images/honey.png',
        ]);
    }
}
