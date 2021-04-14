<?php

namespace Database\Seeders;

use DB;
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
        DB::table('product_categories')->insert([
            [
                'name' => 'روغن حیوانی',
                'slug' => 'روغن-حیوانی',
                'image' => 'images/oil.png',
                'created_at' => now(),
                'created_at' => now(),
            ],
            [
                'name' => 'کره حیوانی',
                'slug' => 'کره-حیوانی',
                'image' => 'images/butter.png',
                'created_at' => now(),
                'created_at' => now(),
            ],
            [
                'name' => 'متفرقه',
                'slug' => 'متفرقه',
                'image' => 'images/honey.png',
                'created_at' => now(),
                'created_at' => now(),
            ],
        ]);

        if (config('app.env') === 'local') {
            DB::table('product_categories')->insert([
                [
                    'name' => 'روغن گوسفندی',
                    'slug' => 'روغن-گوسفندی',
                    'image' => 'images/oil.png',
                    'parent_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'روغن گاوی',
                    'slug' => 'روغن-گاوی',
                    'image' => 'images/oil.png',
                    'parent_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'کره گوسفندی',
                    'slug' => 'کره-گوسفندی',
                    'image' => 'images/butter.png',
                    'parent_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
