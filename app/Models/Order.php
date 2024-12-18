<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_status',
        'total_price',
        'discounted_price',
        'shipping_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,        // Target model (Product)
            OrderItem::class,      // Intermediate model (OrderItem)
            'order_id',             // Foreign key on the `order_items` table
            'id',                  // Foreign key on the `products` table
            'id',                    // Local key on the `orders` table
            'product_id'     // Local key on the `order_items` table
        );
    }
}
