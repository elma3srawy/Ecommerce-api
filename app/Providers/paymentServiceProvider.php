<?php

namespace App\Providers;

use App\Services\Payments\Stripe;
use App\Interfaces\Payment\Payment;
use Illuminate\Support\ServiceProvider;

class paymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(Payment::class, Stripe::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
