<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use App\Events\SmsNotification;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendSmsNotification;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;

define('PAGINATE' , 20);
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        JsonResource::withoutWrapping();
        Event::listen(
            SmsNotification::class,
            SendSmsNotification::class,
        );
    }
}
