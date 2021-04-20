<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author_name' => $this->faker->randomElement(['علیرضا', 'امیر', 'رضا', 'عرفان']),
            'author_email' => $this->faker->email,
            'body' => \Faker\Factory::create('fa_IR')->realText(100),
            'score' => rand(0, 5),
            'status' => rand(1, 3),
            'is_testimonial' => rand(1, 100) > 50,
        ];
    }
}
