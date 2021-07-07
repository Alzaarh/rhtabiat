<?php

namespace Database\Factories;

use App\Models\Comment;
use Faker\Factory as Faker;
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
            'author_name' => $this->faker
                ->randomElement(
                    explode(
                        ' ',
                        'علیرضا امیر فرزانه سارا دانیال عرفان')
                ),

            'author_email' => $this->faker->email,

            'body' => Faker::create('fa_IR')->realText(),

            'score' => rand(0, 5),

            'status' => rand(1, 3),

            'is_testimonial' => rand(1, 100) > 50,
        ];
    }
}
