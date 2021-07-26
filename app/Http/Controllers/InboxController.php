<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;

class InboxController extends Controller
{
    public function index()
    {
        $oClient = Client::account('default');
        $oClient->connect();

        //Get INBOX Mailboxes
        $oFolder = $oClient->getFolder('INBOX');

        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        $aMessage = $oFolder->query()->since(now()->subDays(30))->get();

        $messages = [];
        
        /** @var \Webklex\IMAP\Message $oMessage */
        foreach ($aMessage->toArray() as $oMessage) {
            $struct = [
                'uid' => $oMessage->getUid(),
                'from' => $oMessage->getFrom()[0],
                'subject' => $oMessage->getSubject()[0],
                'body' => $oMessage->getHTMLBody(true),
            ];

            array_push($messages, $struct);
            $oMessage->bodies['html'];
        }

        $messages = array_reverse($messages);

        //dd($messages);

        return inertia('Inbox/Index', compact('messages'));
    }
}
