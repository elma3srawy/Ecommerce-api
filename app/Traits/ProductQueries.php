<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait ProductQueries
{

    protected static function storeProductGetIdQuery($data)
    {
        return  DB::table('products')->insertGetId($data);
    }
    protected static function storeAttributeProductQuery($data)
    {
        DB::table('product_attributes')->insert($data);
    }
    protected static function updateProductQuery($id , $data)
    {
        DB::table('products')->where('id' , '=' , $id)->update($data);
    }
    protected static function updateAttributeProductQuery($product_id, $data)
    {
        static $hasDeleted = false;

        if (!$hasDeleted) {
            self::deleteAttributeProductByProductIdQuery($product_id);
            $hasDeleted = true;
        }

        self::storeAttributeProductQuery($data);
    }
    private static function deleteAttributeProductByProductIdQuery($id)
    {
        DB::table('product_attributes')->where('product_id' ,$id)->delete();
    }
    protected static function deleteProductByProductIdQuery($product_id)
    {
        static $hasDeleted = false;

        if (!$hasDeleted) {
            self::deleteAttributeProductByProductIdQuery($product_id);
            $hasDeleted = true;
        }

        DB::table('products')->where('id' ,$product_id)->delete();
    }
    protected static function getImageByIdQuery($product_id)
    {
        return DB::table('products')->where('id' , '=' , $product_id)->value('image');
    }
    protected static function changeImageByIdQuery($product_id , $path)
    {
        return DB::table('products')->where('id' , '=' , $product_id)->update(['image' => $path]);
    }
    protected static function getAllProductsQuery()
    {
        return DB::table('products')
        ->leftJoin('product_attributes' , 'products.id' , '=' ,'product_attributes.product_id')
        ->leftJoin('categories' , 'products.category_id' , '=' ,'categories.id')
        ->groupBy('products.id', 'price' , 'description', 'stock_quantity','products.image','name' ,'categories.name','categories.status')
        ->select(
            'products.id As id',
            'products.name',
            'products.description',
            'products.price',
            'products.stock_quantity',
            DB::raw("GROUP_CONCAT(attribute_name) as attribute_names"),
            DB::raw("GROUP_CONCAT(attribute_value) as attribute_values"),
            'image',
            'categories.name AS category_name',
            'categories.status AS category_status'
        )
        ->paginate(PAGINATE);
    }
    protected static function getProductByIdQuery($id)
    {
        return DB::table('products')
        ->leftJoin('product_attributes' , 'products.id' , '=' ,'product_attributes.product_id')
        ->leftJoin('categories' , 'products.category_id' , '=' ,'categories.id')
        ->groupBy('products.id', 'price' , 'description', 'stock_quantity','products.image','name' ,'categories.name','categories.status')
        ->select(
            'products.id As id',
            'products.name',
            'products.description',
            'products.price',
            'products.stock_quantity',
            DB::raw("GROUP_CONCAT(attribute_name) as attribute_names"),
            DB::raw("GROUP_CONCAT(attribute_value) as attribute_values"),
            'image',
            'categories.name AS category_name',
            'categories.status AS category_status'
        )
        ->where('products.id' , '=' , $id)
        ->first();
    }
    public static function getPriceByProductIdQuery($product_id)
    {
        return DB::table('products')->where('id' , '=' , $product_id)->value('price');
    }

    protected static function getPricingHistoryByProductIdQuery($product_id)
    {

        return DB::table('product_pricing_history')
        ->leftJoin('admins', function ($join) {
            $join->on('product_pricing_history.changeable_id', '=', 'admins.id')
                 ->where('product_pricing_history.changeable_type', '=', \App\Models\Admin::class);
        })
        ->leftJoin('staffs', function ($join) {
            $join->on('product_pricing_history.changeable_id', '=', 'staffs.id')
                 ->where('product_pricing_history.changeable_type', '=', \App\Models\Staff::class);
        })
        ->where('product_pricing_history.product_id', '=', $product_id)
        ->select(
            'product_pricing_history.product_id',
            'product_pricing_history.old_price',
            'product_pricing_history.new_price',
            'product_pricing_history.changed_at',
            'admins.name as admin_name',
            'admins.id as admin_id',
            'staffs.name as staff_name',
            'staffs.id as staff_id',
        )
        ->get();
    }

    public static function decrementProductStockQuantityByProductId($product_id , $amount)
    {
        DB::table('products')->where('id' , '=' , $product_id)->decrement('stock_quantity' , $amount);
    }
    public static function IncrementProductStockQuantityByProductId($product_id , $amount)
    {
        DB::table('products')->where('id' , '=' , $product_id)->increment('stock_quantity' , $amount);
    }

    public static function getQuantityByProductIdQuery($product_id)
    {
        return DB::table('products')->where('id' , '=' , $product_id)->value('stock_quantity');
    }

}
