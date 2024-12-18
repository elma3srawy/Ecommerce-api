<?php

namespace App\Rules;

use Closure;
use App\Traits\ProductQueries;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class IsActiveProduct implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
     public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $active = DB::table('products')
        ->leftJoin('categories' , 'products.category_id' , 'categories.id')
        ->select('categories.status')
        ->where('products.id' , $value)
        ->value('status');
        if(!$active)
        {
            $fail('product not active');
        }
    }

}
