<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Cashier\Cashier;
use Laravel\Sanctum\Sanctum;
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
        Cashier::useCustomerModel(User::class);
    }
}
