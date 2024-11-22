<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait CouponQueries
{
    protected function storeCouponQuery($data)
    {
        DB::table('coupons')->insert($data);
    }
    protected function updateCouponQuery($data)
    {
        DB::table('coupons')->where('id' , '=' , $data['id'])->update($data);
    }
    protected function deleteCouponQuery($id)
    {
        DB::table('coupons')->delete($id);
    }
    protected function getAllCouponsQuery()
    {
        return DB::table('coupons')->paginate(PAGINATE);
    }
    protected function getCouponByIdQuery($id)
    {
        return DB::table('coupons')->where('id', '=', $id)->first();
    }
}
