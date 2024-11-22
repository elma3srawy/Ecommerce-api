<?php

namespace App\Repository;

use App\Traits\CouponQueries;
use App\Interfaces\Repository\CouponRepositoryInterface;


class CouponRepository implements CouponRepositoryInterface
{
    use CouponQueries;

    private $now;
    public function __construct()
    {
        $this->now = now();
    }
    public function store($data)
    {
        $data['created_at'] = $this->now;
        return $this->storeCouponQuery($data);
    }
    public function update($data)
    {
        $data['updated_at'] = $this->now;
        return $this->updateCouponQuery($data);
    }
    public function delete($id)
    {
        return $this->deleteCouponQuery($id);
    }
    public function getAllCoupons()
    {
        return $this->getAllCouponsQuery();
    }
    public function getCouponById($id)
    {
        return $this->getCouponByIdQuery($id);
    }
}
