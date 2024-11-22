<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SmsNotification;
use Twilio\Rest\Client;


class SendSmsNotification
{
    private string $sid;
    private string $token;
    private string $fromNumber;

    /**
     * Create a new listener instance.
     */
    public function __construct()
    {
        $this->sid = env('TWILIO_SID');
        $this->token = env('TWILIO_TOKEN');
        $this->fromNumber = env('TWILIO_FROM');
    }

    /**
     * Handle the event.
     */
    public function handle(SmsNotification $event): void
    {
        $client = new Client($this->sid, $this->token);

        $client->messages->create('+2' . $event->number, [
            'from' => $this->fromNumber,
            'body' => $this->setMessage() . $event->code,
        ]);
    }

    /**
     * Define the message.
     */
    private function setMessage(): string
    {
        return 'code is ';
    }
}
