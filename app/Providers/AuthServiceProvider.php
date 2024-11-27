<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Order;
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
        Gate::define('user-update-order', function (User $user ,$order) {
            return $order->order_status === 'pending' && $order->user_id === $user->id;
        });

        Gate::define('user-cancel-order', function (User $user ,$order) {
            return $order->order_status === 'pending' && $order->user_id === $user->id;
        });
    }
}
