<?php

namespace App\Http\Controllers;

use App\Services\Contracts\WhatsAppAPI;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function sendMessage(WhatsAppAPI $whatsApp, $number)
    {
        $whatsApp->sendMessage($number);
    }
}
