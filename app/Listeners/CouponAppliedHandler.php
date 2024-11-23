<?php

namespace App\Listeners;

use App\Traits\OrderQueries;
use App\Events\CouponApplied;
use App\Traits\CouponQueries;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CouponAppliedHandler
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
    public function handle(CouponApplied $event): void
    {
        CouponQueries::incrementUsageCountByCouponIdQuery($event->coupon_id);
        OrderQueries::calcDiscountedPriceByOrderIdQuery($event->order_id , $this->calcDiscountedPrice($event->coupon_id , $event->order_id));
    }


    private function calcDiscountedPrice($coupon_id , $order_id)
    {

        $totalPrice = OrderQueries::getTotalPriceByOrderIdQuery($order_id);

        $coupon = CouponQueries::getCouponAttributeByCouponIdQuery($coupon_id , "discount_type" , "value");

        if($coupon->discount_type === 'percentage' ){
            $discountAmount = ($coupon->value * $totalPrice) / 100;
        }else{
            $discountAmount = $coupon->value;
        }

        $discountedPrice = $totalPrice - $discountAmount;

        // Ensure the discounted price is not negative
        return  max(0, $discountedPrice);
    }
}
