<?php

namespace Database\Factories;

use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    protected $model = UserDetail::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'username' => $this->faker->firstName,
            'email' => $this->faker->unique()->email,
            'password' => 'password123',
        ];
    }
}
