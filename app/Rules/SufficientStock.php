<?php

namespace App\Rules;

use Closure;
use App\Traits\ProductQueries;
use Illuminate\Contracts\Validation\ValidationRule;

class SufficientStock implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $index = explode('.', $attribute)[1];
        $productId = request("items.$index.product_id");
        $quantity = ProductQueries::getQuantityByProductIdQuery($productId);
        if ($quantity < $value) {
            $fail("The requested quantity exceeds available stock for product ID {$productId}.");
        }
    }

}
