<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrackOrderStatusChange
{


    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusChanged $event): void
    {
        $auth = Auth::user();

        DB::table('order_status_history')->insert([
            'order_id' => $event->order_id,
            'status' => $event->status,
            'changeable_type' => get_class($auth),
            'changeable_id' => $auth->id,
            'changed_at' => now(),
        ]);
    }
}
