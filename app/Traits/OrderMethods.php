<?php

namespace App\Traits;

use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Orders\ChangeOrderStatusRequest;

trait OrderMethods
{
    use OrderQueries , ResponseTrait;

    public function getAllOrders()
    {
        return $this->successResponse($this->getAllOrdersQuery() ,"Orders retrieved successfully.");
    }

    public function getOrdersByStatus(string $status)
    {
        return $this->successResponse($this->getOrdersByStatusQuery($status) ,"Orders retrieved successfully.");
    }


    public function getOrdersByDateRange(string $startDate, string $endDate)
    {
        return $this->successResponse($this->getOrdersByDateRangeQuery($startDate , $endDate) ,"Orders retrieved successfully.");
    }

    public function changeStatus(ChangeOrderStatusRequest $request)
    {
        try{
            $order_id = $request->order_id;
            $status = $request->status;
            $this->changeStatusQuery($order_id, $status);
            event(new OrderStatusChanged($order_id , $status));
            DB::commit();
            return $this->successResponse(message: "Order status updated successfully.");
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(message: 'Error status order: ' . $e->getMessage());
        }

    }

}
