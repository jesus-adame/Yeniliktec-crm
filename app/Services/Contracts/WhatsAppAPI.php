<?php

namespace App\Services\Contracts;

interface WhatsAppAPI
{
    public function sendMessage($number);
}