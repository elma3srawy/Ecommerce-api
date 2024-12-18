<?php


use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Middleware\EnsureGuestForAPI;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Reviews\ReviewController;
use App\Http\Controllers\Supports\TicketController;
use App\Http\Controllers\Payments\PaymentsController;
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

        Route::controller(ReviewController::class)->group(function(){
            Route::post('/review/store' , 'store');
        });

        Route::controller(TicketController::class)->group(function(){
            Route::post('/support/ticket/store' , 'store');
        });

        Route::controller(PaymentsController::class)->group(function(){
            Route::post('/order-checkout', 'pay');
            Route::get('/checkout/success', 'success')->name('success');
            Route::get('/checkout/cancel', 'cancel')->name('cancel');
        });

    });

});

Route::controller(CartController::class)->group(function(){
    Route::post('/cart/add-to-cart',  'addToCart');
    Route::post('/cart/remove',  'removeFromCart');
    Route::get('/cart',  'getCart');
    Route::delete('/cart/clear', 'clearCart');
});
