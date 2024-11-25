<?php

namespace App\Providers;

use App\Events\SmsNotification;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendSmsNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            SmsNotification::class,
            SendSmsNotification::class,
        );
    }
}
