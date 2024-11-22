<?php

namespace App\Interfaces\Repository;


interface CouponUserRepositoryInterface extends RepositoryInterface
{
    public function getAllCouponUsers();
    public function getCouponUserByCouponId(string $coupon_id);
}
