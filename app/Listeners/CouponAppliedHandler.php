<?php

namespace App\Listeners;

use App\Traits\OrderQueries;
use App\Events\CouponApplied;
use App\Traits\CouponQueries;
use App\Traits\OrderItemsQueries;
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
        if($event->coupon_id && $event->order_id)
        {
            CouponQueries::incrementUsageCountByCouponIdQuery($event->coupon_id);
            OrderQueries::calcDiscountedPriceByOrderIdQuery($event->order_id , calcDiscountedPrice($event->coupon_id , $event->order_id));
        }
    }

}
