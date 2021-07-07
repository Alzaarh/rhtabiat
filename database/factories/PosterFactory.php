<?php

namespace Database\Factories;

use App\Models\Poster;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosterFactory extends Factory
{
    protected $model = Poster::class;

    public function definition()
    {
        return [
            'image' => 'images/poster.jpg',
        ];
    }
}
