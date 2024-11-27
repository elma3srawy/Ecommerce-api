<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait OrderItemsQueries
{
    protected  static function storeOrderItemsQuery($data)
    {
        return DB::table('order_items')->insert($data);
    }

    protected static function deleteOrderItemsByOrderIdQuery($order_id)
    {
        return DB::table('order_items')->where('order_id','=',$order_id)->delete();
    }

    public static function getOrderItemsByOrderIdQuery($order_id)
    {
        return DB::table('order_items')->where('order_id','=',$order_id)->get(['product_id' , 'quantity']);
    }
    public static function getTotalPriceByOrderIdQuery($order_id)
    {
        return DB::table('order_items')->where('order_id','=',$order_id)->sum('price');
    }

}

