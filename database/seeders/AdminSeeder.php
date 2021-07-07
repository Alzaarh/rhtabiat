<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'username' => 'erfan.pouretemad',
            'password' => 'xw9LmL3pDW',
            'role' => Admin::ROLES['admin'],
        ]);

        if (config('app.env') === 'local') {
            Admin::create([
                'username' => 'account',
                'password' => 'password123',
                'role' => Admin::ROLES['accountant'],
            ]);
            Admin::create([
                'username' => 'writer',
                'password' => 'password123',
                'role' => Admin::ROLES['writer'],
            ]);
            Admin::create([
                'username' => 'discount',
                'password' => 'password123',
                'role' => Admin::ROLES['discount_generator'],
            ]);
        }
    }
}
