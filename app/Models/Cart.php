<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    public function getCart()
    {
        return DB::table('carts')
        ->rightJoin('cart_items' , 'carts.id' , '=' , 'cart_items.cart_id')
        ->leftJoin('products' , 'cart_items.product_id' , '=' , 'products.id')
        ->leftJoin('product_attributes' , 'products.id' , '=' , 'product_attributes.product_id')
        ->where('carts.id' , '=' , $this->id)
        ->select(
            'cart_items.product_id',
                'cart_items.quantity' ,
                'products.price' ,
                'products.image',
                DB::raw('GROUP_CONCAT(product_attributes.attribute_name SEPARATOR ", ") AS attribute_names'),
                DB::raw('GROUP_CONCAT(product_attributes.attribute_value SEPARATOR ", ") AS attribute_values')
            )
        ->groupBy('cart_items.id')
        ->get();
    }

    public static function findCart($auth_id, $session_id)
    {
        return static::where('user_id', $auth_id)
            ->orWhere('session_id', $session_id)
            ->first();
    }

}
