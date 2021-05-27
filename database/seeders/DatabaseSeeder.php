<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
//        if (config('app.env') === 'local') {
//            $this->call([
//                BannerSeeder::class,
//                ProductCategorySeeder::class,
//                ProductSeeder::class,
//                ProductItemSeeder::class,
//                AdminSeeder::class,
//                ArticleCategorySeeder::class,
//                CommentSeeder::class,
//            ]);
//        }
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,
        ]);
    }
}
