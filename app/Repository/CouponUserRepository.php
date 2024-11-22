<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Traits\CouponUsersQueries;
use App\Interfaces\Repository\CouponUserRepositoryInterface;

class CouponUserRepository implements CouponUserRepositoryInterface
{
    use CouponUsersQueries;
    private Carbon $now;

    public function __construct()
    {
        $this->now = now();
    }
    public function store($data)
    {
        return collect($data['coupons'])
        ->unique()
        ->each(function ($validated) {
            $validated['created_at'] = $this->now;
            $this->storeCouponUsersQuery($validated);
        });
    }
    public function update($data)
    {
        $coupon_id = $data['coupon_id'];
        $this->deleteCouponUsersByCouponIdQuery($coupon_id);

        collect($data['user_ids'])
        ->unique()
        ->each(function ($user_id) use($coupon_id) {
            $validated['coupon_id'] = $coupon_id;
            $validated['user_id'] = $user_id;
            $validated['updated_at'] = $this->now;
            $this->storeCouponUsersQuery($validated);
        });
    }
    public function delete($coupon_id)
    {
        $this->deleteCouponUsersByCouponIdQuery($coupon_id);
    }

    public function getAllCouponUsers()
    {
        return $this->getAllCouponUsersQuery();
    }
    public function getCouponUserByCouponId(string $coupon_id)
    {
        return $this->getCouponUserByCouponIdQuery($coupon_id);
    }
}
