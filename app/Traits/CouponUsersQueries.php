<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait CouponUsersQueries
{
    protected function storeCouponUsersQuery($data)
    {
        DB::table('coupon_user')->insert($data);
    }
    protected function deleteCouponUsersByCouponIdQuery($coupon_id)
    {
        DB::table('coupon_user')->where('coupon_id' , '=' , $coupon_id)->delete();
    }

    protected function getAllCouponUsersQuery()
    {
        return DB::table('coupon_user')
        ->join('users' , 'coupon_user.user_id' , 'users.id')
        ->join('coupons' , 'coupon_user.coupon_id' , 'coupons.id')
        ->select('coupon_id' ,
            'user_id',
            'name',
            'code',
            'discount_type',
            'value',
            'minimum_order_value',
            'start_date' ,'end_date',
            'usage_limit',
            'usage_count',
            'is_active'
        )
        ->paginate(PAGINATE);
    }
    protected function getCouponUserByCouponIdQuery($coupon_id)
    {
        return DB::table('coupon_user')
        ->join('users' , 'coupon_user.user_id' , 'users.id')
        ->join('coupons' , 'coupon_user.coupon_id' , 'coupons.id')
        ->where('coupon_user.coupon_id' , '=',$coupon_id)
        ->select(
            'coupon_id',
            'user_id',
            'name',
            'code',
            'discount_type',
            'value',
            'minimum_order_value',
            'start_date' ,'end_date',
            'usage_limit',
            'usage_count',
            'is_active'
        )
        ->paginate(PAGINATE);
    }
}
