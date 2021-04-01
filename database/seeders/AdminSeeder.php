<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([Admin::ADMIN, Admin::ACCOUNTANT, Admin::WRITER, Admin::DISCOUNT_GENERATOR])
            ->each(fn ($role) => Admin::factory(2)->create(['role' => $role]));
    }
}
