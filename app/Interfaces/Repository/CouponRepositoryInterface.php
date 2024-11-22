<?php

namespace App\Interfaces\Repository;


interface CouponRepositoryInterface extends RepositoryInterface
{
    public function getAllCoupons();
    public function getCouponById($id);
}
