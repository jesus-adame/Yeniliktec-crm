<?php

namespace App\Services;

use App\Services\Contracts\WhatsAppAPI;
use Twilio\Rest\Client;

class TwillioWhatsApp implements WhatsAppAPI
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            config('twillio.auth_sid'),
            config('twillio.auth_token')
        );
    }

    public function sendMessage($number)
    {
        $this->client->messages->create(
            // the number you'd like to send the message to
            'whatsapp:+521'. $number,
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => 'whatsapp:+14155238886',
                // the body of the text message you'd like to send
                'body' => "Tu cita es el 21 de Julio a las 3PM"
            ]
        );
    }
}