<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ImapAdapter;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    private $client;

    public function __construct(ImapAdapter $imapClient)
    {
        $this->client = $imapClient;
    }

    public function index()
    {
        return inertia('Inbox/Index');
    }

    public function getMessages()
    {
        $page = request()->input('page');

        $IMAPmessages = $this->client->getInboxMessages($page);

        $messages = array_reverse($IMAPmessages[0]->toArray());
        $links = array_values($IMAPmessages[1]->elements[0]);
        $currentPage = $IMAPmessages[1]->paginator->currentPage();

        return response(compact('messages', 'links', 'currentPage'));
    }

    public function show($mesageId)
    {
        $message = $this->client->showMessage($mesageId);
        return response()->json(compact('message'));
    }
}
