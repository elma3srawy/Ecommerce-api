<?php

namespace App\Repository;

use App\Traits\OrderQueries;
use App\Events\CouponApplied;
use App\Events\ProductStockAdjusted;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Repository\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    use OrderQueries;

    private $auth;
    private \Carbon\Carbon $now;
    public function __construct()
    {
        $this->now = now();
        $this->auth = Auth::user();
    }
    public function store($validate)
    {
        $data = collect($validate)
        ->when(isset($validate['code']), function ($collection) {
            return $collection
            ->put('coupon_id', $this->getCouponIdByCode($collection->get('code')))
            ->except('code');
        })
        ->except('items')
        ->put('user_id' , $this->auth->id)
        ->put('created_at' , $this->now);

        $order_id = $this->storeOrderGetIdQuery($data->all());

        $items = collect( $validate['items']);

        $this->storeOrderItems($order_id , $items);


        if(isset($data["coupon_id"]))
        {
            event(new CouponApplied($data["coupon_id"] , $order_id));
        }
    }

    private function storeOrderItems($order_id, $items)
    {
        collect($items)
        ->unique(fn($item) => $item['product_id'])
        ->each(function($item) use ($order_id){
            $item['order_id'] = $order_id;
            $item['price'] = (float) $this->getPriceByProductId($item['product_id']) * $item['quantity'];
            $item['created_at'] = $this->now;

            $this->storeOrderItemsQuery($item);

            event(new ProductStockAdjusted($item['product_id'], $item['quantity']));
        });
    }
    public function update($validate)
    {

    }
    public function delete($validated)
    {

    }



}
