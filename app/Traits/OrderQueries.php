<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait OrderQueries
{
    protected static function storeOrderGetIdQuery($data)
    {
        return  DB::table('orders')->insertGetId($data);
    }

    public static function calcDiscountedPriceByOrderIdQuery($order_id , $discounted_price)
    {
        return DB::table('orders')->where('id','=',$order_id)->update(['discounted_price' => $discounted_price]);
    }

    public static function getColumnByOrderIdQuery($order_id , $column_name)
    {
        return DB::table('orders')->where('id','=',$order_id)->value($column_name);
    }

    protected static function updateStatusOrderByOrderIdQuery($order_id , $status = "pending")
    {
        return DB::table('orders')->where('id','=',$order_id)->update(['order_status' => $status]);
    }

    public static function updateOrderByOrderIdQuery($order_id , $data)
    {
        return DB::table('orders')->where('id','=',$order_id)->update($data);
    }

    protected static function getAllOrdersQuery()
    {
        return DB::table('orders')
        ->leftJoin('users' , 'orders.user_id' , '=' , 'users.id')
        ->leftJoin('coupons' , 'orders.coupon_id' , '=' , 'coupons.id')
        ->select(
            'orders.id as order_id',
            'orders.order_status',
            'orders.total_price',
            'orders.discounted_price',
            'orders.shipping_address',
            'orders.created_at as order_date',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone as user_phone',
            'coupons.code as coupon_code',
            'coupons.discount_type',
            'coupons.value as discount_value'
        )
        ->paginate(PAGINATE);
    }
    protected static function changeStatusQuery(string $order_id , string $status)
    {
        return DB::table('orders')->where('id','=',$order_id)->update(['order_status' => $status]);
    }

    public static function getOrderByOrderIdQuery($order_id)
    {
        return DB::table('orders')->where('id','=',$order_id)->first();
    }
    protected  static function getOrdersByStatusQuery(string $status)
    {
        return DB::table('orders')
        ->leftJoin('users' , 'orders.user_id' , '=' , 'users.id')
        ->leftJoin('coupons' , 'orders.coupon_id' , '=' , 'coupons.id')
        ->where('order_status','=',$status)
        ->select(
            'orders.id as order_id',
            'orders.order_status',
            'orders.total_price',
            'orders.discounted_price',
            'orders.shipping_address',
            'orders.created_at as order_date',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone as user_phone',
            'coupons.code as coupon_code',
            'coupons.discount_type',
            'coupons.value as discount_value'
        )
        ->paginate(PAGINATE);
    }
    protected  static function getOrdersByDateRangeQuery(string $start_date ,string $end_date)
    {
        return DB::table('orders')
        ->leftJoin('users' , 'orders.user_id' , '=' , 'users.id')
        ->leftJoin('coupons' , 'orders.coupon_id' , '=' , 'coupons.id')
        ->whereBetween('orders.created_at',[$start_date , $end_date])
        ->select(
            'orders.id as order_id',
            'orders.order_status',
            'orders.total_price',
            'orders.discounted_price',
            'orders.shipping_address',
            'orders.created_at as order_date',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone as user_phone',
            'coupons.code as coupon_code',
            'coupons.discount_type',
            'coupons.value as discount_value'
        )
        ->paginate(PAGINATE);
    }

    public static function getOrderPaymentByOrderIdQuery(int $order_id)
    {
        return  DB::table('Order_items')
        ->rightJoin('products' , 'order_items.product_id' , '=' , 'products.id')
        ->where('order_id' , '=' , $order_id)
        ->select('order_items.quantity' , 'stripe_price_id')
        ->get();
    }   
}

