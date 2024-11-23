<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Interfaces\Repository\OrderRepositoryInterface;


class OrderController extends Controller
{
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

            return response()->json([
                'message' => 'Order placed successfully',
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Error placing order: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function update()
    {
        // return $this->order->update();
    }
    public function destroy()
    {
        // return $this->order->delete();
    }
}
