<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricingHistory extends Model
{
    use HasFactory;

    protected $table = 'product_pricing_history';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'old_price',
        'new_price',
        'changeable_id',
        'changeable_type',
        'changed_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
