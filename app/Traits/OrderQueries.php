<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait OrderQueries
{
    use OrderItemsQueries;
    protected static function storeOrderGetIdQuery($data)
    {
        return  DB::table('orders')->insertGetId($data);
    }

    public static function calcDiscountedPriceByOrderIdQuery($order_id , $discounted_price)
    {
        return DB::table('orders')->where('id','=',$order_id)->update(['discounted_price' => $discounted_price]);
    }

    public static function getTotalPriceByOrderIdQuery($order_id)
    {
        return DB::table('orders')->where('id','=',$order_id)->value('total_price');
    }

    public static function getColumnByOrderIdQuery($order_id , $column_name)
    {
        return DB::table('orders')->where('id','=',$order_id)->value($column_name);
    }

    protected static function updateStatusOrderByOrderIdQuery($order_id , $status = "pending")
    {
        return DB::table('orders')->where('id','=',$order_id)->update(['order_status' => $status]);
    }

    protected static function updateOrderByOrderIdQuery($order_id , $data)
    {
        return DB::table('orders')->where('id','=',$order_id)->update($data);
    }
}

