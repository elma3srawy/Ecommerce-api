<?php

namespace App\Http\Controllers\Orders;

use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Requests\Orders\CancelOrderRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Interfaces\Repository\OrderRepositoryInterface;
use App\Traits\OrderMethods;

class OrderController extends Controller
{
    use ResponseTrait , OrderMethods;
    public function __construct(private OrderRepositoryInterface $order)
    {

    }
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $validated['total_price'] = $request->getTotalPrice();

        DB::beginTransaction();
        try {
            $this->order->store($validated);

            DB::commit();

            return $this->successResponse(message:'Order placed successfully' , statusCode:201);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(message: 'Error placing order: ' . $e->getMessage());
        }
    }
    public function update(UpdateOrderRequest $request)
    {

        $validated = $request->validated();

        try {

            $this->order->update($validated);

            DB::commit();

            return $this->successResponse(message:'Order updated successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(message: 'Error placing order: ' . $e->getMessage());
        }

    }
    public function destroy()
    {

    }

    public function cancelOrder(CancelOrderRequest $request)
    {
        try {
            $this->order->cancelOrder($request->validated('order_id'));
            DB::commit();
            return  $this->successResponse(message:'Order cancelled successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(message: 'Error Cancel order: ' . $e->getMessage());
        }
    }


}
