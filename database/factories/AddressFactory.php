<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'company' => $this->faker->company,
            'mobile' => '0901' . $this->faker->randomNumber(7),
            'phone' => '0513' . $this->faker->randomNumber(7),
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'zipcode' => $this->faker->randomNumber(7) . '000',
            'address' => $this->faker->address,
        ];
    }
}
