<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ImapAdapter;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(ImapAdapter $ImapClient)
    {
        $messages = $ImapClient->getInboxMessages(10);

        return inertia('Inbox/Index', compact('messages'));
    }
}
