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
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->email,
            'body' => $this->faker->realText(100),
            'score' => rand(0, 5),
            'status' => rand(1, 3),
        ];
    }
}
