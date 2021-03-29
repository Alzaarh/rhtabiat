<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->email,
            'body' => $this->faker->sentence(20),
            'score' => rand(0, 5),
            'status' => rand(1, 3),
        ];
    }
}
