<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Banner;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Poster;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        collect(Admin::ROLES)->each(function ($value) {
            Admin::factory()->create(['role' => $value]);
        });

        Banner::factory(5)->create();
        Banner::factory()->create(['is_active' => true]);

        Poster::factory(5)->create();

        Category::factory(3)->hasChildren(2)->create();
        Category::factory(4)->create();

        Product::factory(5)->hasFeatures(1)->hasComments(3)->create();
        Product::factory(3)->hasFeatures(6)->create();
        Product::factory(3)
            ->hasFeatures(6, ['container' => 'zink'])
            ->hasFeatures(6, ['container' => 'plastic'])
            ->create();

        BlogCategory::factory(5)->hasArticles(3)->create();
    }
}
