<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureGuestForAPI;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Authentication\User\AuthController;
use App\Http\Controllers\Authentication\VerificationController;
use App\Http\Controllers\Authentication\User\ResetPasswordController;



Route::middleware(EnsureGuestForAPI::class)->group(function()
{
    Route::controller(AuthController::class)->group(function(){
        Route::post('/register' , 'register');
        Route::post('/login' , 'login');
    });
    Route::middleware('throttle:10,5')->controller(ResetPasswordController::class)->group(function(){
        Route::post('/forget-password/' , 'forgetPassword');
        Route::put('/reset-password' , 'resetPassword');
    });
});


Route::middleware(['auth:sanctum' , 'abilities:user'])->group(function()
{

    Route::controller(AuthController::class)->group(function(){
        Route::get('/me' , 'me');
        Route::delete('/logout' , 'logout');
    });

    Route::middleware('throttle:10,5')->controller(VerificationController::class)->group(function(){
        Route::post('/send-notification' , 'sendNotification');
        Route::post('/verify-phone-number/{code}' , 'verify');
    });

    Route::middleware( 'verified')->group(function()
    {
        Route::controller(OrderController::class)->group(function(){
            Route::post('/order/store' , 'store');
            Route::put('/order/update' , 'update');
            Route::put('/order/cancel', 'cancelOrder');
        });

    });


});
