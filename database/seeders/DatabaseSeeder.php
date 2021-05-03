<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ProductCategorySeeder::class,
            ProductSeeder::class,
            ProductItemSeeder::class,
            AdminSeeder::class,
            ArticleCategorySeeder::class,
            CommentSeeder::class,
        ]);

        $this->call([
            BannerSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
        ]);
    }
}
