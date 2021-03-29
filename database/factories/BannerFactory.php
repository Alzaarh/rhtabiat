<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4) . '...',
            'subtitle' => $this->faker->paragraph(3),
            'image' => 'images/banner.jpg',
            'link_text' => $this->faker->sentence(3),
            'link_dest' => $this->faker->url,
        ];
    }
}
