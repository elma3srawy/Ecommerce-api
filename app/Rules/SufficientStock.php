<?php

namespace App\Rules;

use Closure;
use App\Traits\ProductQueries;
use Illuminate\Contracts\Validation\ValidationRule;

class SufficientStock implements ValidationRule
{

    public function __construct(protected $productId)
    {

    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $quantity = ProductQueries::getQuantityByProductId($this->productId);
        if ($quantity < $value) {
            $fail("The requested quantity exceeds available stock for product ID {$this->productId}.");
        }
    }

}
