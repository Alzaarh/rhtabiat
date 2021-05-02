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
            CommentSeeder::class,
        ]);

        $this->call([
            BannerSeeder::class,
            AdminSeeder::class,
            ArticleCategorySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
        ]);
    }
}
