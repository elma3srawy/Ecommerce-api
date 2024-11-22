<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SmsNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $code;
    public string $number;

    /**
     * Create a new event instance.
     */
    public function __construct(string $code, string $number)
    {
        $this->code = $code;
        $this->number = $number;
    }

}
