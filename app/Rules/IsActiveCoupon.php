<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class IsActiveCoupon implements ValidationRule
{

    public function __construct(private $totalValue)
    {

    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $coupon = $this->getCouponByCode($value);

        if (!$coupon) {
            $fail('The coupon does not exist.');
            return;
        }
        if (!(
            $coupon->is_active &&                             // Must be active
            $coupon->end_date > now() &&                      // Must not be expired
            $coupon->usage_limit > $coupon->usage_count &&    // Must not exceed usage limit
            $this->totalValue >= $coupon->minimum_order_value // Must meet minimum order value
        )) {
            $fail('The coupon is not valid for use.');
        }
    }


    private function getCouponByCode($code)
    {
        return DB::table('coupons')
        ->where('code' , $code)
        ->select('minimum_order_value' , 'end_date' , 'usage_limit' , 'usage_count' , 'is_active')
        ->first();
    }
}

