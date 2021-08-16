<?php

namespace App\Services;

use App\Services\Contracts\ImapAdapter;
use Webklex\PHPIMAP\ClientManager;

class PhpImapAdapter implements ImapAdapter
{
    protected $client;

    public function __construct()
    {
        $this->client = new ClientManager(base_path('/config/imap.php'));
    }

    /**
     * Return all inbox messages
     */
    public function getInboxMessages($days = 30, $inboxPath = 'INBOX')
    {
        $sinceDate = now()->subDays($days);

        $this->connect();

        //Get INBOX Mailboxes
        $mailFolder = $this->getFolder($inboxPath);

        //Get all Messages of the current Mailbox $mailFolders
        $mailMessages = $mailFolder->query()
            ->since($sinceDate)
            ->limit(50)
            ->get();

        $this->disconnect();

        $formated = $mailMessages->map(function ($message) {
            return [
                'date' => $message->getDate()[0]->format('Y-m-d H:m:s'),
                'uid' => $message->getUid(),
                'from' => $message->getFrom()[0],
                'subject' => $message->getSubject()[0],
                'body' => $message->getHTMLBody(true),
            ];
        });

        return $formated->sortDesc();
    }

    public function writeMessage($message)
    {
        # code...
    }

    public function showMessage($messageId)
    {
        # code...
    }

    public function moveMessage($messageId)
    {
        # code...
    }

    public function deleteMessage($messageId)
    {
        # code...
    }

    /**
     * @return \Webklex\PHPIMAP\Folder
     */
    private function getFolder($inboxPath)
    {
        return $this->client->getFolder($inboxPath);
    }

    private function connect()
    {
        $this->client->connect();
    }

    private function reconnect()
    {
        $this->client->reconnect();
    }

    private function disconnect()
    {
        $this->client->disconnect();
    }
}