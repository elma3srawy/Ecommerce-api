<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureGuestForAPI;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Coupons\CouponController;
use App\Http\Controllers\Supports\TicketController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Coupons\CouponUserController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Authentication\Admin\AuthController;
use App\Http\Controllers\Authentication\VerificationController;
use App\Http\Controllers\Authentication\Admin\ResetPasswordController;

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


Route::middleware(['auth:sanctum' , 'abilities:admin'])->group(function()
{

    Route::controller(AuthController::class)->group(function($route){
        Route::get('/me' , 'me');
        Route::delete('/logout' , 'logout');
    });

    Route::middleware('throttle:10,5')->controller(VerificationController::class)->group(function(){
        Route::post('/send-notification' , 'sendNotification');
        Route::post('/verify-email/{token}' , 'verify');
    });

    Route::middleware( 'verified')->group(function()
    {
        Route::controller(CategoryController::class)->group(function(){
            Route::post('/category/store' , 'store');
            Route::post('/category/update' , 'update');
            Route::delete('/category/delete' , 'destroy');
            Route::get('/categories' , 'getAllCategories');
            Route::get('/sub-categories' , 'getSubCategories');
            Route::get('/parent-categories' , 'getParentCategories');
            Route::get('/get-category/{category_id}' , 'getCategoryById');
            Route::get('/get-active/category' , 'getActiveCategory');
            Route::get('/get-inactive/category' , 'getInActiveCategory');
        });
        Route::controller(ProductController::class)->group(function(){
            Route::post('/product/store' , 'store');
            Route::post('/product/update' , 'update');
            Route::delete('/product/delete' , 'destroy');
            Route::get('/product/get-all' , 'getAllProducts');
            Route::get('/product/get/{id}' , 'getProductById');
            Route::put('/product/change-price' , 'changePrice');
            Route::post('/product/change-image' , 'changeImage');
            Route::get('/product/{id}/pricing-history' , 'getPricingHistory');
        });
        Route::controller(CouponController::class)->group(function(){
            Route::post('/coupon/store' , 'store')->name('coupon.store');
            Route::put('/coupon/update' , 'update')->name('coupon.update');
            Route::delete('/coupon/delete' , 'destroy')->name('coupon.destroy');
            Route::get('/coupon/get-all' , 'getAllCoupons');
            Route::get('/coupon/{id}/get' , 'getCouponById');
        });
        Route::controller(CouponUserController::class)->group(function(){
            Route::post('/coupon/users/store' , 'store');
            Route::put('/coupon/user/update' , 'update');
            Route::delete('/coupon/users/delete' , 'destroy');
            Route::get('/coupon/users/get-all' , 'getAllCouponUsers');
            Route::get('/coupon/{coupon_id}/users/' , 'getCouponUserByCouponId');
        });

        Route::controller(OrderController::class)->group(function(){
            Route::put('/order/change/status' , 'changeStatus');
            Route::get('/order/all' , 'getAllOrders');
            Route::get('/order/status/{status}' , 'getOrdersByStatus');
            Route::get('/order/date/{startDate}/{endDate}' , 'getOrdersByDateRange');
        });

        Route::controller(TicketController::class)->group(function(){
            Route::put('/support/ticket/change/status' , 'update');
        });

    });


});


