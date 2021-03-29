<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        $fakerFa = Faker::create('fa_IR');
        return [
            'receiver' => $fakerFa->name,
            'company' => $fakerFa->company,
            'mobile' => '0910' . $this->faker->randomNumber(7),
            'phone' => '0513' . $this->faker->randomNumber(7),
            'state' => $this->faker->randomElement(['تهران', 'خراسان رضوی', 'کرمان']),
            'city' => $this->faker->randomElement(['مشهد', 'تهران', 'کرمان']),
            'zipcode' => $this->faker->randomNumber(7) . '000',
            'address' => Str::of($fakerFa->realText())->words(10, ''),
        ];
    }
}
