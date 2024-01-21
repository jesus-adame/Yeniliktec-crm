<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PhpImapAdapter;
use App\Services\Contracts\ImapAdapter;

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
        $account = request()->input('account');

        $client = $this->getClient($account);
        $IMAPmessages = $client->getInboxMessages($page);
        
        $messages = array_reverse($IMAPmessages[0]->toArray());
        $links = array_values($IMAPmessages[1]->elements[0]);
        $currentPage = $IMAPmessages[1]->paginator->currentPage();

        return response(compact('messages', 'links', 'currentPage'));
    }

    public function show($messageId)
    {
        $account = request()->input('account');

        $client = $this->getClient($account);
        $message = $client->showMessage($messageId);

        return response()->json(compact('message'));
    }

    private function getClient($account)
    {
        if ($account == 'contact') {
            $client = new PhpImapAdapter($account);
        } else {
            $client = $this->client;
        }
        return $client;
    }
}
