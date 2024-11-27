<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use App\Traits\OrderQueries;
use App\Rules\IsActiveCoupon;
use App\Traits\CouponQueries;
use App\Traits\OrderItemsQueries;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Validation\ValidationException;

class OrderUpdatedHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderUpdated $event): void
    {

        $order_id = $event->order_id;

        $total_price = OrderItemsQueries::getTotalPriceByOrderIdQuery($order_id);

        $order = DB::table('orders')
        ->leftJoin('coupons' , 'orders.coupon_id' , '=' , 'coupons.id')
        ->where('orders.id' ,'=' ,$order_id)
        ->first(['minimum_order_value', 'coupons.id AS coupon_id']);


        if (!($total_price >= $order->minimum_order_value)) {
            throw ValidationException::withMessages([
                'total_price' => ['The total price must be greater than or equal to the minimum order value.']
            ]);
        }
        OrderQueries::updateOrderByOrderIdQuery($order_id ,
             [
                'total_price' => $total_price,
                'discounted_price' => calcDiscountedPrice($order->coupon_id , $order_id),
             ]);

    }
}
