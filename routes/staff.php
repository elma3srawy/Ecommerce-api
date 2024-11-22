<?php


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureGuestForAPI;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Authentication\Staff\AuthController;
use App\Http\Controllers\Authentication\VerificationController;
use App\Http\Controllers\Authentication\Staff\ResetPasswordController;



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


Route::middleware(['auth:sanctum' , 'abilities:staff'])->group(function()
{

    Route::controller(AuthController::class)->group(function(){
        Route::get('/me' , 'me');
        Route::delete('/logout' , 'logout');
    });

    Route::middleware('throttle:10,5')->controller(VerificationController::class)->group(function(){
        Route::post('/send-notification' , 'sendNotification');
        Route::post('/verify-mobile/{token}' , 'verify');
    });

    Route::middleware( 'verified')->group(function()
    {
        Route::controller(ProductController::class)->group(function(){
            Route::post('/product/store' , 'store');
            Route::post('/product/update' , 'update');
            Route::get('/product/get-all' , 'getAllProducts');
            Route::get('/product/get/{id}' , 'getProductById');
            Route::put('/product/change-price' , 'changePrice');
            Route::post('/product/change-image' , 'changeImage');
        });
    });


});
