<?php

namespace App\Http\Controllers\Coupons;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Interfaces\Repository\CouponRepositoryInterface;

class CouponController extends Controller
{
    use ResponseTrait;

    public function __construct(protected CouponRepositoryInterface $coupon){}

    public function store(CouponRequest $request)
    {
        $this->coupon->store($request->validated());
        return $this->successResponse(message:'Coupon created successfully!');
    }
    public function update(CouponRequest $request)
    {
        $this->coupon->update($request->validated());
        return $this->successResponse(message:'Coupon updated successfully!');
    }
    public function destroy(CouponRequest $request)
    {
        $this->coupon->delete($request->validated('id'));
        return $this->successResponse(message:'Coupon deleted successfully!');
    }

    public function getAllCoupons()
    {
        $coupons = $this->coupon->getAllCoupons();
        return $this->successResponse($coupons , 'Coupons retrieved successfully');
    }
    public function getCouponById(string $id)
    {
        $coupon = $this->coupon->getCouponById($id);
        return $this->successResponse($coupon , 'Coupon retrieved successfully');
    }

}
