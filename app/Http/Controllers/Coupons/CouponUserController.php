<?php

namespace App\Http\Controllers\Coupons;

use Exception;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponUsersRequest;
use App\Interfaces\Repository\CouponUserRepositoryInterface;

class CouponUserController extends Controller
{
    use ResponseTrait;
    public function __construct(protected CouponUserRepositoryInterface $couponUser){}

    public function store(CouponUsersRequest $request)
    {
        try {
            $this->couponUser->store($request->validated());

            return $this->successResponse(message: 'Coupon-user relation created successfully.');
        } catch (Exception $e) {

            return $this->errorResponse(message: 'An error occurred while creating the coupon-user relation.');
        }
    }

    public function update(CouponUsersRequest $request)
    {
        DB::beginTransaction();

        try {

            $this->couponUser->update($request->validated());

            DB::commit();
            return $this->successResponse(message: 'Coupon-user relation updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            return $this->errorResponse(message: 'An error occurred while updating the coupon-user relation.');
        }
    }

    public function destroy(CouponUsersRequest $request)
    {
        try {

            $this->couponUser->delete($request->validated('coupon_id'));

            return $this->successResponse(message: 'Coupon-user relation deleted successfully.');
        } catch (Exception $e) {

            return $this->errorResponse(message: 'An error occurred while deleting the coupon-user relation.');
        }
    }

    public function getAllCouponUsers()
    {
        return $this->successResponse($this->couponUser->getAllCouponUsers(), 'Coupon-user relation retrieved successfully.');

    }
    public function getCouponUserByCouponId(string $coupon_id)
    {
        return $this->successResponse($this->couponUser->getCouponUserByCouponId($coupon_id), 'Coupon-user relation retrieved successfully.');
    }

}
