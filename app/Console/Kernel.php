<?php

namespace App\Console;

use App\Jobs\NotifyViaSms;
use App\Models\Guest;
use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        Guest::where('created_at', '<=', now()->subHours(12))
            ->whereDoesntHave('orders', function ($query) {
                $query->where('created_at', '>=', now()->subHours(2))
                    ->whereHas('order', function ($query) {
                        $query->whereStatus(Order::STATUS['not_paid']);
                    });
            })
            ->whereHasSentSms(false)
            ->get()
            ->each(function ($guest) use ($schedule) {
                $guest->has_sent_sms = true;
                $guest->save();
                $schedule->job(
                    new NotifyViaSms(
                        $guest->phone,
                        config('app.sms_patterns.order_waiting'),
                        ['keyword' => 'اعتماد']
                    )
                )
                ->everySixHours();
            });
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
