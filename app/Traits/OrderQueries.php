<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait OrderQueries
{
    protected static function storeOrderGetIdQuery($data)
    {
        return  DB::table('orders')->insertGetId($data);
    }
    protected static function getCouponIdByCode($code)
    {
        return  DB::table('coupons')->where('code' , '=' , $code)->value("id");
    }

    public static function getPriceByProductId($id):float
    {
        return DB::table('products')->where('id','=',$id)->value('price');
    }

    public static function calcDiscountedPriceByOrderIdQuery($order_id , $discounted_price)
    {
        return DB::table('orders')->where('id','=',$order_id)->update(['discounted_price' => $discounted_price]);
    }
    protected  static function storeOrderItemsQuery($data)
    {
        return DB::table('order_items')->insert($data);
    }

    public static function getTotalPriceByOrderIdQuery($order_id)
    {
        return DB::table('orders')->where('id','=',$order_id)->value('total_price');
    }
}

