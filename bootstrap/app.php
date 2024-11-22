<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureUserVerified;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api:__DIR__.'/../routes/user.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
            Route::middleware('api')
                ->prefix('api/staff')
                ->name('staff.')
                ->group(base_path('routes/staff.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->api([StartSession::class]);
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'verified' => EnsureUserVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e) {
            return response()->json([
                'error' => 'You do not have permission to access this resource.'
            ], 403);
        });
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e) {
            return response()->json([
                'error' => 'Too many requests, please try after ' . $e->getHeaders()['Retry-After'] . ' seconds'
            ], 429);
        });

    })->create();
