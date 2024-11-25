<?php

namespace App\Listeners;

use App\Traits\ProductQueries;
use Illuminate\Support\Facades\DB;
use App\Events\ProductStockAdjusted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StockQuantityHandler
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
    public function handle(ProductStockAdjusted $event): void
    {   if($event->action === 'increment')
        {
            ProductQueries::IncrementProductStockQuantityByProductId($event->product_id ,$event->quantity);
        }else{
            ProductQueries::decrementProductStockQuantityByProductId($event->product_id ,$event->quantity);
        }
    }
}
