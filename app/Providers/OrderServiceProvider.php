<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Order\Validation;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Validation::class, function ($app) {
            return new Validation();
        });
    }

    public function boot()
    {
        //
    }
}
