<?php

namespace App\Repository;

use App\Traits\OrderQueries;
use App\Events\CouponApplied;
use App\Traits\CouponQueries;
use App\Rules\SufficientStock;
use App\Traits\ProductQueries;
use App\Traits\OrderItemsQueries;
use App\Events\OrderStatusChanged;
use App\Events\OrderUpdated;
use App\Events\ProductStockAdjusted;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Repository\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    use OrderQueries,
    OrderItemsQueries,
    CouponQueries,
    ProductQueries;

    private $auth;
    private \Carbon\Carbon $now;
    public function __construct()
    {
        $this->now = now();
        $this->auth = Auth::user();
    }
    public function store($validated)
    {
        $data = collect($validated)
        ->when(isset($validated['code']), function ($collection) {
            return $collection
            ->put('coupon_id', $this->getCouponIdByCodeQuery($collection->get('code')))
            ->except('code');
        })
        ->except('items')
        ->put('user_id' , $this->auth->id)
        ->put('created_at' , $this->now);

        $order_id = $this->storeOrderGetIdQuery($data->all());

        $items = collect( $validated['items']);

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
            $item['price'] = (float) $this->getPriceByProductIdQuery($item['product_id']) * $item['quantity'];

            $this->storeOrderItemsQuery($item);

            event(new ProductStockAdjusted($item['product_id'], $item['quantity']));
        });
    }
    public function update($validated)
    {
        $order_id = $validated['order_id'];

        $this->deleteOrderItemsByOrderIdQuery($order_id);

        $this->storeOrderItems($order_id , $validated['items']);

        $this->updateOrderByOrderIdQuery($order_id, ['shipping_address' => $validated['shipping_address'] , 'updated_at' => $this->now]);

        event(new OrderUpdated($order_id));
    }

    public function delete($order_id)
    {

    }
    public function cancelOrder($order_id)
    {
        $this->updateStatusOrderByOrderIdQuery($order_id , 'cancelled');
        event(new OrderStatusChanged($order_id , 'cancelled'));
    }



}
