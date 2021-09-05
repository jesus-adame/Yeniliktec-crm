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
    public function getInboxMessages($page = null, $inboxPath = 'INBOX')
    {
        $this->connect();

        //Get INBOX Mailboxes
        $mailFolder = $this->getFolder($inboxPath);

        //Get all Messages of the current Mailbox $mailFolders
        $mailMessages = $mailFolder->query()
            ->all()
            ->setFetchBody(false)
            ->limit(100)
            ->paginate(5, $page, 'page');

        $this->disconnect();

        $formated = $mailMessages->map(function ($message) {
            if (mb_detect_encoding($message->getSubject()[0]) == 'ASCII') {
                $subject = $this->decodeHeader($message->getSubject()[0]);
            }
            
            if (mb_detect_encoding($message->getSubject()[0]) == 'UTF-8') {
                $subject = mb_convert_encoding($message->getSubject()[0], "UTF-8", ["ASCII", 'UTF-8']);
            }

            return [
                'date' => $message->getDate()[0]->format('Y-m-d H:m:s'),
                'uid' => $message->getUid(),
                'from' => $this->decodeHeader($message->getFrom()[0]->full),
                'subject' => str_replace('_', ' ', $subject),
            ];
        });

        return [$formated, $mailMessages->links()];
    }

    public function writeMessage($message)
    {
        # code...
    }

    public function showMessage($messageId, $inboxPath = 'INBOX')
    {
        //Get INBOX Mailboxes
        $mailFolder = $this->getFolder($inboxPath);
        $message = $mailFolder->query()->getMessageByUid($messageId);   

        if (mb_detect_encoding($message->getSubject()[0]) == 'ASCII') {
            $subject = $this->decodeHeader($message->getSubject()[0]);
        }
        
        if (mb_detect_encoding($message->getSubject()[0]) == 'UTF-8') {
            $subject = mb_convert_encoding($message->getSubject()[0], "UTF-8", ["ASCII", 'UTF-8']);
        }

        return [
            'from' => [
                'personal' => $this->decodeHeader($message->getFrom()[0]->personal),
                'mailbox' => $message->getFrom()[0]->mailbox,
                'host' => $message->getFrom()[0]->host,
                'mail' => $message->getFrom()[0]->mail,
                'full' => $this->decodeHeader($message->getFrom()[0]->full),
            ],
            'date' => $message->getDate()[0]->format('Y-m-d H:m:s'),
            'uid' => $message->getUid(),
            'subject' => str_replace('_', ' ', $subject),
            'body' => $message->getHTMLBody(true),
            'flags' => $message->flags
        ];
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

    private function decodeHeader($string)
    {
        return iconv_mime_decode($string, 0, 'UTF-8');
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