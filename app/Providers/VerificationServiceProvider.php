<?php

namespace App\Providers;

use App\Services\Verification\VerifyEmail;
use App\Services\Verification\VerifyMobile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Authentication\VerificationInterface;

class VerificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VerificationInterface::class, function ($app) {
            $user = Auth::user();

            if ($user && $user->tokenCan('admin')) {
                return new VerifyEmail();
            } else {
                return new VerifyMobile();
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
