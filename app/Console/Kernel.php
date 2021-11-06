<?php

namespace App\Console;

use App\Jobs\NotifyViaSms;
use App\Models\Guest;
use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Cart;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $carts = Cart::has('products')->whereBetween(
                'updated_at',
                [now()->subHours(12), now()->subHours(11)]
            );
            $users = [];
            foreach ($carts as $cart) {
                $user = $cart->user;
                if ($user->detail) {
                    array_push($users, ['phone' => $user->phone, 'name' => $user->detail->name]);
                } else {
                    array_push($users, ['phone' => $user->phone, 'name' => 'کاربر']);
                }
            }
            foreach ($users as $user) {
                NotifyViaSms::dispatch([
                    'phone' => $user['phone'],
                    'pattern' => config('app.sms_patterns.order_waiting'),
                    ['name' => $user['name'], 'link' => 'https://rhtabiat.ir/checkout/cart']
                ]);
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
