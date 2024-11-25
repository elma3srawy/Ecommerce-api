<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
        Gate::define('user-update-order', function (User $user , $order_status) {
            return $order_status === 'pending';
        });

        Gate::define('user-cancel-order', function (User $user ,$order_status) {
            return $order_status === 'pending';
        });
    }
}
