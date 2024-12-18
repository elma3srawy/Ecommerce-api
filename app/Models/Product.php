<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'image',
        'stripe_product_id',
        'stripe_price_id',
    ];

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function pricingHistory()
    {
        return $this->hasMany(ProductPricingHistory::class);
    }
}
