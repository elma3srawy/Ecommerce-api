<?php
namespace App\Interfaces\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface Payment
{
    public function pay(Request $request);
    public function success(Request $request);
    public function cancel(Request $request);

}
