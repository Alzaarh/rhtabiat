<?php

namespace Database\Seeders;

use App\Models\Banner;
use DB;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banners')->insert([
            [
                'image' => 'images/hero.jpg',
                'location' => Banner::LOCATIONS['hero'],
            ],
            [
                'image' => 'images/banner-1.jpg',
                'location' => Banner::LOCATIONS['home_top_big'],
            ],
            [
                'image' => 'images/banner-2.png',
                'location' => Banner::LOCATIONS['home_top_small'],
            ],
            [
                'image' => 'images/banner-3.png',
                'location' => Banner::LOCATIONS['home_top_small'],
            ],
            [
                'image' => 'images/banner-4.png',
                'location' => Banner::LOCATIONS['home_below'],
            ],
            [
                'image' => 'images/banner-1.png',
                'location' => Banner::LOCATIONS['home_below'],
            ],
            [
                'image' => 'images/banner-1.png',
                'location' => Banner::LOCATIONS['home_mob_slider'],
            ],
            [
                'image' => 'images/banner-2.png',
                'location' => Banner::LOCATIONS['home_mob_slider'],
            ],
            [
                'image' => 'images/banner-3.png',
                'location' => Banner::LOCATIONS['home_mob_slider'],
            ],
            [
                'image' => 'images/banner-4.png',
                'location' => Banner::LOCATIONS['home_mob_slider'],
            ],
            [
                'image' => 'images/banner-1.png',
                'location' => Banner::LOCATIONS['home_mob_small'],
            ],
            [
                'image' => 'images/banner-2.png',
                'location' => Banner::LOCATIONS['home_mob_small'],
            ],
        ]);
    }
}
