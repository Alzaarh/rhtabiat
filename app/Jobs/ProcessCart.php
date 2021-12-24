<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Cart;

class ProcessCart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $carts = Cart::with('user')
            ->has('products')
            ->where('updated_at', '<=', now()->subHours(12))
            ->whereIsSmsSent(false)
            ->get();
        foreach ($carts as $cart) {
            if ($cart->user->detail) {
                $name = $cart->user->detail->name;
            } else {
                $name = 'کاربر';
            }
            NotifyViaSms::dispatch(
                $cart->user->phone,
                config('app.sms_patterns.order_waiting'),
                ['name' => $name, 'link' => 'https://rhtabiat.ir/checkout/cart']
            );
        }
        if ($carts->count() > 0) {
            $carts->toQuery()->update(['is_sms_sent' => true]);
        }
    }
}
