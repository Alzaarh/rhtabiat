<?php

namespace Database\Seeders;

use App\Models\Banner;
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
        foreach (Banner::LOCATIONS as $location) {
            Banner::create(['image' => 'banner.jpg', 'location' => $location]);
        }
    }
}
