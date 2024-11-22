<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\ProductPriceChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordPriceChangeHistory
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
    public function handle(ProductPriceChanged $event): void
    {
        DB::table('product_pricing_history')->insert($event->data);
        DB::table('products')
        ->where('id' , '=' , $event->data['product_id'])
        ->update(['price' => $event->data['new_price']]);
    }
}
