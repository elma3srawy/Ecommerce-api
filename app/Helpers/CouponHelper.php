<?php

use App\Traits\CouponQueries;
use App\Traits\OrderItemsQueries;


if(!function_exists("calcDiscountedPrice")){
    function calcDiscountedPrice($coupon_id , $order_id){
        if($coupon_id && $order_id)
        {
            $totalPrice = OrderItemsQueries::getTotalPriceByOrderIdQuery($order_id);

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
}
