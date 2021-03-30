<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(Order::STATUS_LIST),
            'payment_method' => $this->faker->randomElement(
                Order::PAYMENT_METHOD
            ),
            'code' => Order::generateCode(),
            'address_id' => Address::inRandomOrder()->value('id'),
        ];
    }
}
