<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Interfaces\Payment\Payment;
use App\Http\Controllers\Controller;


class PaymentsController extends Controller
{

    public function __construct(private Payment $payment)
    {

    }
    public function pay(Request $request)
    {

        return $this->payment->pay($request);

    }
    public function success(Request $request)
    {
        $this->payment->success($request);
    }
    public function cancel(Request $request)
    {
        $this->payment->cancel($request);
    }
}
