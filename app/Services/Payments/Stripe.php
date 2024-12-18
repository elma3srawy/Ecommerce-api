<?php
namespace App\Services\Payments;

use App\Models\Order;
use App\Traits\OrderQueries;
use Illuminate\Http\Request;
use App\Interfaces\Payment\Payment;



class Stripe implements Payment
{
    public function pay(Request $request )
    {

        $order = OrderQueries::getOrderPaymentByOrderIdQuery($request->post('order_id'));


        $lineItems = $order->map(function($item) {
            return [
                'price' => $item->stripe_price_id,
                'quantity' => $item->quantity,
            ];
        })->toArray();


        $session = $request->user()->checkout($lineItems, [
            'success_url' => route('success'),
            'cancel_url' => route('cancel'),
        ]);

        return response()->json([
            'checkout_url' => $session->url,
        ]);
    }

    public function success(Request $request)
    {

    }
    public function cancel(Request $request)
    {

    }
}
