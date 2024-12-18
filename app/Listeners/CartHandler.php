<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Events\ItemRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CartHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ItemRemoved $event): void
    {
        $cart = Cart::find($event->cart_id);
        $count = $cart->items()->count();
        if($count === 0)
        {
            $cart->delete();
        }
    }
}
