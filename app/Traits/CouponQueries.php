<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait CouponQueries
{
    protected static function storeCouponQuery($data)
    {
        DB::table('coupons')->insert($data);
    }
    protected static function updateCouponQuery($data)
    {
        DB::table('coupons')->where('id' , '=' , $data['id'])->update($data);
    }
    protected static function deleteCouponQuery($id)
    {
        DB::table('coupons')->delete($id);
    }
    protected static function getAllCouponsQuery()
    {
        return DB::table('coupons')->paginate(PAGINATE);
    }
    protected static function getCouponByIdQuery($id)
    {
        return DB::table('coupons')->where('id', '=', $id)->first();
    }
    public static function incrementUsageCountByCouponIdQuery($coupon_id ,$amount  = 1)
    {
        DB::table('coupons')->where('id' ,'=' , $coupon_id)->increment('usage_count' , $amount);
    }

    public static function getCouponAttributeByCouponIdQuery($coupon_id , ...$columns)
    {
        return DB::table('coupons')->where('id' ,'=' , $coupon_id)->first($columns);
    }

    protected static function getCouponIdByCodeQuery($code)
    {
        return  DB::table('coupons')->where('code' , '=' , $code)->value("id");
    }
    public static function getCodeByCouponIdQuery($coupon_id)
    {
        return  DB::table('coupons')->where('id' , '=' , $coupon_id)->value("code");
    }
}
