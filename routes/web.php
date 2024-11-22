<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::get('/' , function ()
{
    // $startDate = '2024-01-01';
    // $endDate = '2024-01-31';
    // return  DB::table('coupon_user')
    //     ->leftJoin('coupons', 'coupon_user.coupon_id', '=', 'coupons.id')
    //     ->leftJoin('users', 'coupon_user.user_id', '=', 'users.id')
    //     ->select('users.name as user_name', 'coupons.code as coupon_code', 'coupon_user.created_at')
    //     ->get();

});
